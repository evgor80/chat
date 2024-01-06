<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\StoreUserRequest;
use App\Interfaces\IUserService;
use App\DTO\UserDto;
use App\Http\Requests\LoginUserRequest;
use App\DTO\LoginDto;
use App\Http\Requests\UsernameAvailabilityRequest;

class UserController extends BaseController
{
    /**
     * UserController constructor.
     *
     * @param \App\Interfaces\IUserService $userService
     * @return void
     */
    public function __construct(protected IUserService $userService)
    {
    }

    /**
     * Store a new user.
     *
     * @param  \App\Http\Requests\StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $dto = $this->mapToUserDto($request->all());
        $success = $this->userService->registerUser($dto);

        return $this->sendResponse($success, 'Регистрация выполнена.', 201);
    }

    /**
     * Log a given user in
     *
     * @param  \App\Http\Requests\LoginUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        $dto = $this->mapToLoginDto($request->all());
        $success = $this->userService->login($dto);

        return $this->sendResponse($success, 'Вход выполнен.');
    }

    /**
     * Check that a specified username is not occupied.
     *
     * @param  \App\Http\Requests\UsernameAvailabilityRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUsernameAvailability(UsernameAvailabilityRequest $request)
    {
        $success = [];
        $name = $request->name;
        $success['name'] = $name;

        return $this->sendResponse($success, 'Имя не занято.');
    }

    /**
     * Map data from client to UserDTO
     * 
     * @param array<string,string> $data Input from user
     * @return \App\DTO\UserDto
     */
    private function mapToUserDto(array $data)
    {
        return new UserDto(
            $data['username'],
            $data['password'],
        );
    }

    /**
     * Map data from client to LoginDTO
     * 
     * @param array<string,string> $data Input from user
     * @return \App\DTO\LoginDto
     */
    private function mapToLoginDto(array $data)
    {
        return new LoginDto(
            $data['username'],
            $data['password'],
        );
    }
}
