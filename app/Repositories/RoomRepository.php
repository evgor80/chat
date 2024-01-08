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

    public function getAll()
    {
        return Room::withCount('messages')->get();
    }

    public function findOneByName(string $name)
    {
        return Room::with('messages.author:id,username')->where('name', $name)->first();
    }

    public function createMessage(string $name, string $msg, int $userId)
    {
        $room = Room::where('name', $name)->firstOrFail();

        return $room->messages()->create([
            'user_id' => $userId,
            'text' => $msg,
            'type' => 'message'
        ]);
    }
}
