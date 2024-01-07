<?php

namespace App\Interfaces;

use App\DTO\RoomDto;

interface IRoomRepository
{
    /**
     * Save room to database from given DTO object and return it
     * 
     * @param \App\DTO\RoomDto $room
     * @return \App\Models\Room
     */
    public function save(RoomDto $room);

     /**
     * Check if chat room with specified slug exists
     * 
     * @param string $slug
     * @return bool
     */
    public function exists(string $slug);

    /**
     * Find a room by specified slug
     * 
     * @param string $slug
     * @return \App\Models\Room
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOneBySlug(string $slug);
}
