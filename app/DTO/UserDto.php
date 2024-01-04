<?php

namespace App\DTO;

class UserDto
{
    /**
     * UserDto constructor
     * 
     * @param string $username
     * @param string $password
     * @return void
     */
    public function __construct(
        public readonly string $username,
        public string $password = '',
    ) {
    }
}
