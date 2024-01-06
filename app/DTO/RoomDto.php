<?php

namespace App\DTO;

class RoomDto
{
    /**
     * RoomDto constructor
     * 
     * @param string $name Name of the chat room
     * @param bool $private Is chat room public or private
     * @param string $password Password for private room
     * @param string $slug Slug for room's url address
     * @return void
     */
    public function __construct(
        public readonly string $name,
        public bool $private = false,
        public string $password = '',
        public string $slug = ''
    ) {
    }
}
