<?php

namespace App\Services;

use App\DTO\RoomDto;
use App\Interfaces\IRoomRepository;
use App\Interfaces\IRoomService;
use App\Utils\SlugGenerator;
use Illuminate\Support\Facades\Hash;

class RoomService implements IRoomService
{
    /**
     * RoomService constructor.
     *
     * @param \App\Interfaces\IRoomRepository $roomRepository
     * @return void
     */
    public function __construct(protected IRoomRepository $roomRepository)
    {
    }
    
    public function registerRoom(RoomDto $room)
    {
        $room->slug = $this->generateSlug($room->name);
        if (!empty($room->password)) {
            $room->password = Hash::make($room->password);
        }

        return $this->roomRepository->save($room);
    }

    public function getBySlug(string $slug)
    {
        return $this->roomRepository->findOneBySlug($slug);
    }

    /**
     * Generate unique slug for url address from given chatroom's name
     * 
     * @param string $name
     * @return string
     */
    private function generateSlug(string $name)
    {
        $slug = SlugGenerator::generate($name);
        //if generated slug already exists, continue to add postfix to it until it'll become unique
        while($this->roomRepository->exists($slug)) {
            $slug = $slug . '_1';
        }

        return $slug;
    }
}
