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
}
