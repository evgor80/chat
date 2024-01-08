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
}