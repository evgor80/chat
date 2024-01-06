<?php

namespace App\Interfaces;

use App\DTO\RoomDto;

interface IRoomService
{
    /**
     * Register new chat room from given dto object and return it
     * 
     * @param \App\DTO\RoomDto $room
     * @return \App\Models\Room
     */
    public function registerRoom(RoomDto $room);
}
