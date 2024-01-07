<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Interfaces\IChatService;
use App\Facades\JwToken;
use App\Exceptions\NotAuthenticatedException;
use Carbon\Carbon;
use App\Models\Room;
use App\DTO\ChatRoomDto;

class ChatServiceTest extends TestCase
{
    use DatabaseMigrations;

    protected string $token;

    protected User $user;

    protected IChatService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(IChatService::class);
        $this->user = User::factory()->create();
        $this->token = JwToken::generateJwt($this->user);
    }

    public function test_throws_exception_if_user_is_invalid(): void
    {
        try {
            $user = new User();
            $user->name = 'test11';
            $user->id = 11;
            $token = JwToken::generateJwt($user);
            $this->service->getAllRooms($token);
        } catch (NotAuthenticatedException $e) {
            $this->assertInstanceOf(NotAuthenticatedException::class, $e);
        }
    }

    public function test_throws_exception_if_token_is_expired(): void
    {
        try {
            Carbon::setTestNow(Carbon::now()->subMinutes(190));
            $token = JwToken::generateJwt($this->user);
            Carbon::setTestNow(Carbon::now());
            $this->service->getAllRooms($token);
        } catch (NotAuthenticatedException $e) {
            $this->assertInstanceOf(NotAuthenticatedException::class, $e);
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
        $rooms = $this->service->getAllRooms($this->token);
        $this->assertCount(3, $rooms);
        $this->assertInstanceOf(ChatRoomDto::class, $rooms[0]);
        $this->assertEquals(1, $rooms[0]->messages);
    }
}