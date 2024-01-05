<?php

namespace App\Interfaces;

use App\DTO\UserDto;
use App\DTO\LoginDto;

interface IUserService
{
    /**
     * Register a new user.
     * 
     * @param \App\DTO\UserDto $user
     * @return array<string,string> Return newly created user and generated token for this user
     */
    public function registerUser(UserDto $user);

    /**
     * Log a user in or throw exception if credentials are invalid
     *
     * @param  \App\DTO\LoginDto $user
     * @return array<string, string> Return user fetched from database and generated token for this user
     * @throws \App\Exceptions\FailedRequestException
     */
    public function login(LoginDto $user);
}
