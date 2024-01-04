<?php

namespace App\Interfaces;

use App\DTO\UserDto;

interface IUserRepository
{
    /**
     * Save new user to the database from provided dto object
     * 
     * @param \App\DTO\UserDto $user
     * @return \App\Models\User
     */
    public function save(UserDto $user);

}