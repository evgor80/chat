<?php

namespace App\Interfaces;

use App\DTO\UserDto;


interface IUserService
{
    /**
     * Register a new user.
     * 
     * @param \App\DTO\UserDto $user
     * @return array<string,string> Return newly created user and generated token for this user
     */
    public function registerUser(UserDto $user);
}
