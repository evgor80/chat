<?php

namespace Tests\Feature\Repositories;

use App\DTO\RoomDto;
use App\Interfaces\IRoomRepository;
use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RoomRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    protected IRoomRepository $repo;

    public function setUp(): void
    {
        parent::setUp();

        $this->repo = $this->app->make(IRoomRepository::class);
    }

    public function test_saves(): void
    {
        $room = $this->repo->save(
            new RoomDto(
                'Main',
                false,
                '',
                'main',
            )
        );
        $this->assertInstanceOf(Room::class, $room);
        $this->assertEquals('Main', $room->name);
        $this->assertEquals(1, $room->id);
    }

    public function test_returns_true_if_room_exists(): void
    {
        Room::factory()->create();
        $is_exist = $this->repo->exists('main');
        $this->assertTrue($is_exist);
    }

    public function test_returns_false_if_room_doesnt_exist(): void
    {
        $is_exist = $this->repo->exists('main');
        $this->assertFalse($is_exist);
    }
}
