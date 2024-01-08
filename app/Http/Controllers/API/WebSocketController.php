<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Interfaces\IChatService;
use App\Exceptions\NotAuthenticatedException;
use App\Exceptions\InvalidWebSocketMessageException;
use App\Validation\ChatConnectionValidation;
use App\Exceptions\ChatRoomNotFoundException;
use App\Exceptions\NotAuthorizedException;
use App\Exceptions\InvalidAuthorException;

class WebSocketController extends Controller implements MessageComponentInterface
{
    /**
     * WebSocketController constructor
     *
     * @param \App\Interfaces\IChatService $chatService
     * @return void
     */
    public function __construct(protected IChatService $chatService)
    {
    }

    public function onOpen(ConnectionInterface $conn)
    {
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->chatService->removeUser($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        try {
            $message = json_decode($msg, true);
            ChatConnectionValidation::preValidateMessage($message);
            if ($message['type'] == "all-rooms") {
                //if a user wants to get a list of all chat rooms
                $rooms = $this->chatService->getAllRooms($message['token']);
                $from->send(json_encode(["type" => "all", 'rooms' => $rooms]));
            } elseif ($message['type'] == "update") {
                //subscribe a user to updates
                $this->chatService->subscribeToUpdates($from, $message['token']);
            } elseif ($message['type'] == "user-joined") {
                //if a user wants to join certain chat room
                $results = $this->chatService->addUser($from, $message);
                /**
                 * send to newly connected user a list of messages in the chat room
                 *       and a list of currently connected users
                 */
                $from->send(
                    json_encode([
                        "type" => 'welcome',
                        'members' => $results['members'],
                        'messages' => $results['messages']
                    ])
                );
            } elseif ($message['type'] == "message") {
                //If user sends a message to ceratin room
                $this->chatService->addMessage($message);
            }        
        } catch (NotAuthenticatedException |
            InvalidAuthorException $e) {
            $from->send(json_encode(["type" => "401"]));
        } catch (InvalidWebSocketMessageException $e) {
            $from->send(json_encode(["type" => '422']));
        } catch (ChatRoomNotFoundException $e) {
            $from->send(json_encode(["type" => "404"]));
        } catch (NotAuthorizedException $e) {
            $from->send(json_encode(["type" => 'access-denied']));
        } catch (Exception $e) {
            error_log("An exception has been catched: " . $e->getMessage() . "\n");
            $from->send(json_encode(["type" => "500"]));
        }
    }
    public function onError(ConnectionInterface $conn, Exception $e)
    {
        error_log("An error has occurred: " . $e->getMessage() . "\n");
        $conn->close();
    }
}