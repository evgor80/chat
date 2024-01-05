<?php

namespace App\DTO;

readonly class LoginDto
{
    /**
     * RoomDto constructor
     * 
     * @param string $username
     * @param string $password
     * @return void
     */
    public function __construct(
        public string $username,
        public string $password = '',
    ) {
    }
}
