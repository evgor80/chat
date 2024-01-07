<?php

namespace App\DTO;

use DateTime;

readonly class ChatRoomDto
{
    /**
     * RoomDto constructor
     * 
     * @param int $id Chat room's id in the database
     * @param string $name Name of the chat room
     * @param string $slug Chat room's slug for url address
     * @param bool $private Is chat room public or private
     * @param int $messages Number of messages sent to the chat romm
     * @param int $members Number of users currentlcy connected to the chat romm
     * @param string $created_at When the chat room was created
     * @return void
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public bool $private,
        public int $messages,
        public int $members,
        public DateTime $created_at
    ) {
    }
}