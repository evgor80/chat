<?php

namespace App\Services;

use App\DTO\UserDto;
use App\Facades\JwToken;
use App\Interfaces\IUserRepository;
use App\Interfaces\IUserService;
use Illuminate\Support\Facades\Hash;
use App\DTO\LoginDto;
use App\Exceptions\FailedRequestException;
use Illuminate\Support\MessageBag;

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

    public function login(LoginDto $user)
    {
        $success = [];
        $fetched_user = $this->userRepository->findByUsername($user->username);
        //if user wasn't found or given password is incorrect throw exception with Unauthorised error
        if (!isset($fetched_user) || !$this->isPasswordCorrect($user->password, $fetched_user->password)) {
            throw new FailedRequestException('Unauthorised.', new MessageBag(['error' => 'Неверное имя пользователя или пароль']), 401);
        }
        $success['token'] = JwToken::generateJwt($fetched_user);
        $success['user'] = $fetched_user->username;

        return $success;
    }

    public function getUserById(int $id)
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Check if raw password value matches hashed password value
     * 
     * @param string $password
     * @param string $hashedPassword
     * @return bool
     */
    private function isPasswordCorrect(string $password, string $hashedPassword)
    {
        return Hash::check($password, $hashedPassword);
    }
}