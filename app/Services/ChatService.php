<?php

namespace App\Services;

use Ds\Map;
use App\Interfaces\IChatService;
use App\Interfaces\IRoomRepository;
use App\Interfaces\IUserRepository;
use App\Facades\JwToken;
use App\Models\Room;
use App\DTO\ChatRoomDto;
use App\Exceptions\NotAuthenticatedException;
use Ratchet\ConnectionInterface;
use App\Exceptions\ChatRoomNotFoundException;
use App\Exceptions\NotAuthorizedException;
use App\Validation\ChatConnectionValidation;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Exceptions\InvalidAuthorException;
use App\Utils\MessageSanitizer;

class ChatService implements IChatService
{
    /**
     * @var \Ds\Map<string, \Ds\Map<string, \Ds\Sequence<\Ratchet\ConnectionInterface>>> $rooms 
     * List of active chat rooms with a list of connected users for each of them.
     */
    protected $rooms;

    /**
     * @var \SplObjectStorage $subscribersToUpdates
     * Users subscribed to updates about chat rooms info
     */
    protected $subscribersToUpdates;

    /**
     * ChatService constructor
     * 
     * @param \App\Interfaces\IRoomRepository $roomRepo
     * @param \App\Interfaces\IUserRepository $userRepo
     * @return void
     */
    public function __construct(
        protected IRoomRepository $roomRepo,
        protected IUserRepository $userRepo
    ) {
        $this->rooms = new Map([]);
        $this->subscribersToUpdates = new \SplObjectStorage;
    }

    public function getAllRooms(string $token)
    {
        $this->authenticate($token);
        $rooms = $this->roomRepo->getAll();

        return $rooms->map(function ($room) {
            return $this->mapRoom($room);
        });
    }

    public function subscribeToUpdates(ConnectionInterface $conn, string $token)
    {
        $this->authenticate($token);
        $this->subscribersToUpdates->attach($conn);
    }

    public function addUser(ConnectionInterface $conn, array $message)
    {
        ChatConnectionValidation::validateJoinMessageCompletness($message);
        $user = $this->authenticate($message['token']);
        //get current room data from  database
        $room = $this->getRoom($message['room']);
        //validate provided password if chat room is private
        if ($room->private) {
            $this->authorizeUser($room->password, $message['password']);
        }
        $this->addRoomIfDoesntExist($room->name);
        //add new user to the chat room
        $this->addUserIfDoesntExist($room->name, $user->username);
        //add new connection to user's array
        $this->rooms[$room->name][$user->username]->push($conn);
        //get a list of currently connected users
        $connUsers = $this->getConnectedUsers($room->name);
        //send message about new user's join, only if this is their first entry
        //don't send the message, if user open the same chat room from several tabs/devices
        if (count($this->rooms[$room->name][$user->username]) === 1) {
            //generate a message
            $msg = json_encode([
                "type" => 'user-join',
                'members' => $connUsers,
                'message' => ['user' => $user->username, 'type' => 'join']
            ]);
            //get a list of currently connected sockests
            $users = $this->rooms[$room->name];
            //send the message to every connected socket except newly connected user
            foreach ($users as $username => $connections) {
                if ($username !== $user->username) {
                    foreach ($connections as $c) {
                        $c->send($msg);
                    }
                }
            }
            $this->notifySubscribersToUpdates($room->name);
        }

        return [
            'members' => $connUsers,
            'messages' => $room->messages

        ];
    }

    public function removeUser(ConnectionInterface $conn)
    {
        //unsubscribe a user from updates
        $this->subscribersToUpdates->detach($conn);
        /**
         * iterate over each chat room in the map and every user's sequence, check does it have specified socket.
         * If socket is found, delete it from the sequence. If this is only connection for this user, delete the user from room and generate a message and send it to other users in this room.
         * If room that user leaved was found, return updated info for this room.
         */
        $leavedRoom = '';
        foreach ($this->rooms as $room => $map) {
            foreach ($map as $user => $connections) {
                //find, if sequence with user's connection has given connection
                $i = $connections->find($conn);
                //if it has
                //if index wasn't found, php-ds/php-ds polyfill returns null, native extension returns false
                if (isset($i) && $i !== false) {
                    //remove connection from the sequense
                    $connections->remove($i);
                    //if this was the only connection of this user
                    if (count($connections) === 0) {
                        //remove the user from the chatroom's map
                        $this->rooms[$room]->remove($user);
                        $leavedRoom = $room;
                        $connUsers = $this->getConnectedUsers($room);
                        $msg = json_encode(
                            [
                                "type" => 'user-leave',
                                'members' => $connUsers,
                                'message' => ['user' => $user, 'type' => 'leave']
                            ]
                        );
                        //send message about user leave all connections in this room
                        foreach ($map as $username => $connections) {
                            foreach ($connections as $c) {
                                $c->send($msg);
                            }
                        }
                    }

                }
            }
        }
        //if leaved room was found, send subscribers updated info about this room
        if ($leavedRoom) {
            $this->notifySubscribersToUpdates($leavedRoom);
        }
    }

    public function addMessage(array $msg)
    {
        ChatConnectionValidation::validateChatMessageCompletness($msg);
        $user = $this->authenticate($msg['token']);
        $roomname = $msg['room'];
        $this->validateUser($user, $roomname);
        $sanitizedMessage = MessageSanitizer::sanitize($msg['message']);
        $createdMsg = $this->roomRepo->createMessage($msg['room'], $sanitizedMessage, $user->id);
        $newMsg = json_encode([
            'type' => 'message-broadcast',
            'message' => [
                'type' => 'message',
                'author' => ['username' => $user->username],
                'text' => $sanitizedMessage,
                'created_at' => $createdMsg->created_at
            ]
        ]);
        //get sequences of connections for given room
        $connArrays = $this->rooms[$msg['room']]->values();
        //send every connection newly created message
        foreach ($connArrays as $arr) {
            foreach ($arr as $c)
                $c->send($newMsg);
        }
        $this->notifySubscribersToUpdates($msg['room']);
    }

    /**
     * Check that jwt-token is valid and find and return a user by id from the token
     * 
     * @param string $token
     * @return \App\Models\User|void
     */
    private function authenticate(string $token)
    {
        try {
            $decoded = JwToken::verifyJwt($token);
            $user = $this->userRepo->findById($decoded->id);

            return $user;
        } catch (
            \FireBase\JWT\BeforeValidException |
            \FireBase\JWT\ExpiredException |
            \FireBase\JWT\SignatureInvalidException |
            \Illuminate\Database\Eloquent\ModelNotFoundException |
            InvalidAuthorException |
            \UnexpectedValueException $e) {
            throw new NotAuthenticatedException();
        }
    }

    /**
     * Take given room's data and return object with required data plus number of connected users
     * 
     * @param \App\Models\Room $room
     * @return \App\DTO\ChatRoomDto
     */
    private function mapRoom(Room $room)
    {
        return new ChatRoomDto(
            $room->id,
            $room->name,
            $room->slug,
            $room->private,
            $room->messages_count ?? count($room->messages),
            $this->getConnectedUsers($room->name)->count(),
            $room->created_at
        );
    }

    /**
     * Return a list of users currently connected to room with specified name
     * 
     * @param string $room
     * @return \Ds\Set<string>
     */
    private function getConnectedUsers(string $room)
    {
        $this->addRoomIfDoesntExist($room);

        return $this->rooms[$room]->keys();
    }

    /**
     *  Add the chat room to the map if it doesnt exist yet
     * 
     * @param string $roomname
     * @return void
     */
    private function addRoomIfDoesntExist(string $roomname)
    {
        if (!$this->rooms->hasKey($roomname)) {
            $this->rooms->put($roomname, new Map([]));
        }
    }

    /**
     * Find and return chat room by specified name. If nothing found, throw exception
     * 
     * @param string $roomname
     * @return \App\Models\Room
     * @throws \App\Exceptions\ChatRoomNotFoundException
     */
    private function getRoom(string $roomname)
    {
        $room = $this->roomRepo->findOneByName($roomname);
        if (is_null($room)) {
            throw new ChatRoomNotFoundException();
        }

        return $room;
    }

    /**
     * Check that provided password matches to password for private chat room, if not, throw exception
     * 
     * @param string $savedPassword
     * @param string $receivedPassword
     * @return void
     * @throws \App\Exceptions\NotAuthorizedException
     */
    private function authorizeUser(string $savedPassword, string $receivedPassword)
    {
        $isAuthorized = Hash::check($receivedPassword, $savedPassword);
        //refuse to add new user if password is invalid
        if (!$isAuthorized) {
            throw new NotAuthorizedException();
        }
    }

    /**
     * Add user to the  chat room map if they doesnt exist yet
     * 
     * @param string $roomname
     * @param $username
     * @return void
     */
    private function addUserIfDoesntExist(string $roomname, string $username)
    {
        if (!$this->rooms[$roomname]->hasKey($username)) {
            $this->rooms[$roomname]->put($username, new \Ds\Vector([]));
        }
    }

    /**
     * Send subscribers updated info about chat room with specified name
     * 
     * @param string $name
     * @return void
     */
    private function notifySubscribersToUpdates(string $name)
    {
        $room_info = $this->getRoomInfo($name);
        $msg = json_encode(["type" => "update", 'room' => $room_info]);
        //send all subscribers updated info about the chat room 
        foreach ($this->subscribersToUpdates as $sub) {
            $sub->send($msg);
        }
    }

    /**
     * Return info about chatroom with specified name, including number of connected users and messages
     * 
     * @param string $name
     * @return \App\DTO\ChatRoomDto
     */
    private function getRoomInfo(string $name)
    {
        $room = $this->roomRepo->findOneByName($name);

        return $this->mapRoom($room);
    }

    /**
     * Check that user doesn't try to send a message to chatroom they doesnt subscribed to or that doesn't exist
     * 
     * @param \App\Models\User $user
     * @param string $roomname
     * @return void
     * @throws \App\Exceptions\InvalidAuthorException
     */
    private function validateUser(User $user, string $roomname)
    {
        //If a user tries to send a message to a chat room they doesnt subscribed to or that doesnt exist
        if (
            !$this->rooms->hasKey($roomname) ||
            !$this->rooms[$roomname]->hasKey($user->username)
        ) {
            throw new InvalidAuthorException();
        }
    }
}