<?php

namespace App\Http\Controllers\API;

use App\DTO\RoomDto;
use App\Http\Controllers\API\BaseController;
use App\Http\Requests\RoomNameAvailabilityRequest;
use App\Http\Requests\StoreRoomRequest;
use App\Interfaces\IRoomService;

class RoomController extends BaseController
{
    /**
     * RoomController constructor.
     *
     * @param \App\Interfaces\IRoomService $roomService
     * @return void
     */
    public function __construct(protected IRoomService $roomService)
    {
    }

    /**
     * Return a chat room by its slug.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $slug)
    {
        $room = $this->roomService->getBySlug($slug);

        return $this->sendResponse(
            ['room' => $room],
            'Чат найден.',
            200
        );
    }

    /**
     * Store a new chat room and return it.
     *
     * @param  \App\Http\Requests\StoreRoomRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRoomRequest $request)
    {
        $dto = $this->mapToDto($request->all());
        $room = $this->roomService->registerRoom($dto);

        return $this->sendResponse(
            ['room' => $room],
            'Чат успешно создан',
            201
        );
    }

    /**
     * Check that a specified room name is not occupied.
     *
     * @param  \App\Http\Requests\RoomNameAvailabilityRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkNameAvailability(RoomNameAvailabilityRequest $request)
    {
        $success = [];
        $name = $request->name;
        $success['name'] = $name;

        return $this->sendResponse($success, 'Название не занято.');
    }

    /**
     * Map data from user to DTO
     * 
     * @param array $data Input from user
     * @return \App\DTO\RoomDto
     */
    private function mapToDto(array $data)
    {
        return new RoomDto(
            $data['name'],
            $data['private'],
            $data['password'],
        );
    }
}
