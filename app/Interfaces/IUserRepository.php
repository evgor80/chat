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

    /**
     * Find a user by specified username
     * 
     * @param string $username
     * @return \App\Models\User|null
     */
    public function findByUsername(string $username);

     /**
     * Find a user by specified id, otherwise throw exception
     * 
     * @param int $id
     * @return \App\Models\User
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(int $id);
}