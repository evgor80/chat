<?php

namespace Tests\Feature\Services;

use App\DTO\RoomDto;
use App\Interfaces\IRoomService;
use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoomServiceTest extends TestCase
{
    use DatabaseMigrations;

    protected IRoomService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(IRoomService::class);
    }

    public function test_registers_private_room(): void
    {
        $room = $this->service->registerRoom(
            new RoomDto(
                'Test',
                true,
                '12345678',
            )
        );
        $this->assertInstanceOf(Room::class, $room);
        $this->assertEquals('Test', $room->name);
        $this->assertEquals('test', $room->slug);
        $this->assertTrue(Hash::check('12345678', $room->password));
    }

    public function test_registers_public_room(): void
    {
        $room = $this->service->registerRoom(
            new RoomDto(
                'Test',
                false,
                '',
            )
        );
        $this->assertInstanceOf(Room::class, $room);
        $this->assertEquals('Test', $room->name);
        $this->assertEquals('test', $room->slug);
        $this->assertEquals('', $room->password);
    }

    public function test_generates_unique_slug(): void
    {
        Room::factory()->create(['name' =>'Test', 'slug' => 'test']);
        $room = $this->service->registerRoom(
            new RoomDto(
                'Test!',
                false,
                '',
            )
        );
        $this->assertEquals('test_1', $room->slug);
    }
}
