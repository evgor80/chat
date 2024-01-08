<?php

namespace Tests\Feature\Repositories;

use App\DTO\RoomDto;
use App\Interfaces\IRoomRepository;
use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Message;


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

    public function test_finds_one_by_slug(): void
    {
        Room::factory()->create();
        $room = $this->repo->findOneBySlug('main');
        $this->assertEquals('Main', $room->name);
        $this->assertEquals(1, $room->id);
    }

    public function test_throws_exception_if_room_wasnt_found_by_slug(): void
    {
        try {
            $this->repo->findOneBySlug('main');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ModelNotFoundException::class, $e);
        }
    }

    public function test_gets_all_rooms(): void
    {
        $new_rooms = Room::factory()->count(3)->sequence(
            ['name' => 'First', 'slug' => 'first'],
            ['name' => 'Second', 'slug' => 'second'],
            ['name' => 'Third', 'slug' => 'third']
        )->create();
        $new_rooms[0]->messages()->create(['user_id' => 1, 'type' => 'message', 'text' => 'test']);
        $rooms = $this->repo->getAll();
        $this->assertCount(3, $rooms);
        $this->assertCount(1, $rooms[0]->messages);
    }

    public function test_finds_one_by_name(): void
    {
        Room::factory()->create();
        $room = $this->repo->findOneByName('Main');
        $this->assertEquals('Main', $room->name);
        $this->assertEquals(1, $room->id);
    }

    public function test_returns_null_if_room_wasnt_found_by_name()
    {
        $room = $this->repo->findOneByName('test');
        $this->assertNull($room);
    }

    public function test_creates_message(): void
    {
        Room::factory()->create();
        $msg = $this->repo->createMessage('Main', 'test', 1);
        $this->assertInstanceOf(Message::class, $msg);
        $this->assertEquals('test', $msg->text);
        $this->assertEquals(1, $msg->id);
    }

    public function test_throws_exception_if_room_doesnt_exist(): void
    {
        try {
            $this->repo->createMessage('Main', 'test', 1);
        } catch (\Exception $e) {
            $this->assertInstanceOf(ModelNotFoundException::class, $e);
        }
    }
}
