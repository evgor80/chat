<?php

namespace App\Services;

use App\DTO\UserDto;
use App\Facades\JwToken;
use App\Interfaces\IUserRepository;
use App\Interfaces\IUserService;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{
    /**
     * UserService constructor.
     *
     * @param \App\Interfaces\IUserRepository $userRepository
     * @return void
     */
    public function __construct(protected IUserRepository $userRepository)
    {
    }

    public function registerUser(UserDto $user)
    {
        $success = [];
        $user->password = Hash::make($user->password);
        $new_user = $this->userRepository->save($user);
        $success['token'] = JwToken::generateJwt($new_user);
        $success['user'] = $new_user->username;

        return $success;
    }
}