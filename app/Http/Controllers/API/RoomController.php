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
