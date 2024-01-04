<?php

namespace App\Repositories;

use App\DTO\UserDto;
use App\Interfaces\IUserRepository;
use App\Models\User;

class UserRepository implements IUserRepository
{
    public function save(UserDto $user)
    {
        $new_user = User::create([
            'username' => $user->username,
            'password' => $user->password
        ]);

        return $new_user;
    }


}