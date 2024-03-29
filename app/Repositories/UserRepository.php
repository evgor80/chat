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

    public function findByUsername(string $username)
    {
        return User::where('username', $username)->first();
    }

    public function findById(int $id)
    {
        return User::findOrFail($id);
    }
}