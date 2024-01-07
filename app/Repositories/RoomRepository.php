<?php

namespace App\Repositories;

use App\DTO\RoomDto;
use App\Interfaces\IRoomRepository;
use App\Models\Room;


class RoomRepository implements IRoomRepository
{
    public function save(RoomDto $room)
    {
        $new_room = Room::create([
            'name' => $room->name,
            'slug' => $room->slug,
            'private' => $room->private,
            'password' => $room->password,
        ]);

        return $new_room;
    }

    public function exists(string $slug)
    {
        return Room::where('slug', $slug)->exists();
    }

    public function findOneBySlug(string $slug)
    {
        return Room::withCount('messages as messages')->where('slug', $slug)->firstOrFail();
    }
}
