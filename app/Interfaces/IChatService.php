<?php

namespace App\Interfaces;

use Ratchet\ConnectionInterface;

interface IChatService
{
    /**
     * Get a list of all chat rooms with numbers of messages and connected users for each of them.
     * 
     * @param string $token
     * @return \Illuminate\Support\Collection<\App\DTO\ChatRoomDto>
     */
    public function getAllRooms(string $token);

    /**
     * Subscribe a user to updated info about chat rooms
     * 
     * @param \Ratchet\ConnectionInterface $conn
     * @param string $token
     * @return void
     */
    public function subscribeToUpdates(ConnectionInterface $conn, string $token);

    /**
     * Connect a user to specified room (if they is authorized) and notify other users in this chat room about new user. Return data about chat room, users online and messages.
     * If requested room wasn't found, throw exception
     * If chat room is protected with password and wrong password was provided, throw exception
     * 
     * @param \Ratchet\ConnectionInterface $conn New user socket
     * @param array $message Decoded JSON message object received from client
     * @return array<string, mixed>
     * @throws \App\Exceptions\ChatRoomNotFoundException
     * @throws \App\Exceptions\NotAuthorizedException
     */
    public function addUser(ConnectionInterface $conn, array $message);

    /**
     * Find corresponding chat room and remove the user from a list of connected users and subscribers to updates
     * 
     * @param \Ratchet\ConnectionInterface $conn Socket of leaving user
     * @return void
     */
    public function removeUser(ConnectionInterface $conn);
}
