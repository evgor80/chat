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

    /**
     * Get a list of all chat rooms
     * 
     * @return \Illuminate\Support\Collection<\App\Models\Room|null>
     */
    public function getAll();

    /**
     * Find a room by specified name
     * 
     * @param string $name
     * @return \App\Models\Room|null
     */
    public function findOneByName(string $name);
}
