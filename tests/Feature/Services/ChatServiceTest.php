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
use App\Exceptions\NotAuthorizedException;
use App\Exceptions\ChatRoomNotFoundException;
use Mockery;
use Ratchet\ConnectionInterface;
use App\Exceptions\InvalidAuthorException;

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

    public function test_adds_user(): void
    {
        $room = Room::factory()->create();
        $room->messages()->create(['user_id' => 1, 'type' => 'message', 'text' => 'test']);
        $conn = Mockery::mock(ConnectionInterface::class);
        $msg = [
            'token' => $this->token,
            'room' => 'Main',
            'password' => '12345678'
        ];
        $results = $this->service->addUser($conn, $msg);
        $this->assertCount(1, $results['members']);
        $this->assertEquals('test', $results['members'][0]);
        $this->assertCount(1, $results['messages']);
        $this->assertEquals('test', $results['messages'][0]['author']['username']);
        $this->assertEquals(1, $results['messages'][0]['author']['id']);
        $this->assertEquals('test', $results['messages'][0]['text']);
    }

    public function test_throws_exception_if_password_for_chatroom_is_invalid(): void
    {
        try {
            Room::factory()->create();
            $conn = Mockery::mock(ConnectionInterface::class);
            $msg = [
                'token' => $this->token,
                'room' => 'Main',
                'password' => '123456789'
            ];
            $this->service->addUser($conn, $msg);
        } catch (NotAuthorizedException $e) {
            $this->assertInstanceOf(NotAuthorizedException::class, $e);
        }
    }

    public function test_throws_exception_if_chatroom_doesnt_exist(): void
    {
        try {
            $conn = Mockery::mock(ConnectionInterface::class);
            $msg = [
                'token' => $this->token,
                'room' => 'Main',
                'password' => '123456789'
            ];
            $this->service->addUser($conn, $msg);
        } catch (ChatRoomNotFoundException $e) {
            $this->assertInstanceOf(ChatRoomNotFoundException::class, $e);
        }

    }

    public function test_notifies_subscriber_about_update(): void
    {
        Room::factory()->create();
        $user = User::factory()->create(['username' => 'test11']);
        $token = JwToken::generateJwt($user);
        /**
         * @var \Ratchet\ConnectionInterface&\Mockery\MockInterface $conn
         */
        $conn = Mockery::mock(ConnectionInterface::class);
        $this->service->subscribeToUpdates($conn, $token);
        $conn1 = Mockery::mock(ConnectionInterface::class);
        $conn->shouldReceive('send')->once()->withArgs(function ($arg) {
            return str_contains($arg, '"type":"update"') &&
                str_contains($arg, '"room":{"id":1,"name":"Main","slug":"main","private":true,"messages":0,"members":1');
        });
        $msg = [
            'token' => $this->token,
            'room' => 'Main',
            'password' => '12345678'
        ];
        $this->service->addUser($conn1, $msg);
    }

    public function test_informs_other_users_in_chatroom_about_new_user(): void
    {
        Room::factory()->create();
        $user = User::factory()->create(['username' => 'test11']);
        $token = JwToken::generateJwt($user);
        /**
         * @var \Ratchet\ConnectionInterface&\Mockery\MockInterface $conn
         */
        $conn = Mockery::mock(ConnectionInterface::class);
        $msg = [
            'token' => $token,
            'room' => 'Main',
            'password' => '12345678'
        ];
        $this->service->addUser($conn, $msg);
        $conn1 = Mockery::mock(ConnectionInterface::class);
        $msg1 = [
            'token' => $this->token,
            'room' => 'Main',
            'password' => '12345678'
        ];
        $conn->shouldReceive('send')->once()
            ->with(json_encode([
                "type" => 'user-join',
                'members' => ['test11', 'test'],
                'message' => ['user' => 'test', 'type' => 'join']
            ]));
        $this->service->addUser($conn1, $msg1);
    }

    public function test_doesnt_inform_other_users_in_chatroom_about_another_connection_from_same_user(): void
    {
        Room::factory()->create();
        $user = User::factory()->create(['username' => 'test11']);
        $token = JwToken::generateJwt($user);
        /**
         * @var \Ratchet\ConnectionInterface&\Mockery\MockInterface $conn
         */
        $conn = Mockery::mock(ConnectionInterface::class);
        $msg = [
            'token' => $token,
            'room' => 'Main',
            'password' => '12345678'
        ];
        $this->service->addUser($conn, $msg);
        $conn1 = Mockery::mock(ConnectionInterface::class);
        $msg1 = [
            'token' => $this->token,
            'room' => 'Main',
            'password' => '12345678'
        ];
        $conn->shouldReceive('send')->once();
        $this->service->addUser($conn1, $msg1);
        $conn2 = Mockery::mock(ConnectionInterface::class);
        $this->service->addUser($conn2, $msg1);
    }

    public function test_doesnt_inform_users_about_their_own_join(): void
    {
        Room::factory()->create();
        /**
         * @var \Ratchet\ConnectionInterface&\Mockery\MockInterface $conn
         */
        $conn = Mockery::mock(ConnectionInterface::class);
        $msg = [
            'token' => $this->token,
            'room' => 'Main',
            'password' => '12345678'
        ];
        $conn->shouldReceive('send')->never();
        $this->service->addUser($conn, $msg);
    }

    public function test_removes_user_and_informs_other_users_in_this_room(): void
    {
        Room::factory()->create();
        $user = User::factory()->create(['username' => 'test11']);
        $token = JwToken::generateJwt($user);
        $conn = Mockery::mock(ConnectionInterface::class);
        /**
         * @var \Ratchet\ConnectionInterface&\Mockery\MockInterface $conn
         */
        $conn->shouldReceive('send')->once();
        $msg = [
            'token' => $token,
            'room' => 'Main',
            'password' => '12345678'
        ];
        $this->service->addUser($conn, $msg);
        /**
         * @var \Ratchet\ConnectionInterface&\Mockery\MockInterface $conn1
         */
        $conn1 = Mockery::mock(ConnectionInterface::class);
        $msg1 = [
            'token' => $this->token,
            'room' => 'Main',
            'password' => '12345678'
        ];
        $this->service->addUser($conn1, $msg1);
        $conn1->shouldReceive('send')->once()->with(json_encode([
            "type" => 'user-leave',
            'members' => ['test'],
            'message' => ['user' => 'test11', 'type' => 'leave']
        ]));
        $this->service->removeUser($conn);
    }

    public function test_doesnt_informs_other_users_in_this_room_if_only_one_of_user_several_sockets_was_closed(): void
    {
        Room::factory()->create();
        $user = User::factory()->create(['username' => 'test11']);
        $token = JwToken::generateJwt($user);
        /**
         * @var \Ratchet\ConnectionInterface&\Mockery\MockInterface $conn
         */
        $conn = Mockery::mock(ConnectionInterface::class);
        /**
         * @var \Ratchet\ConnectionInterface&\Mockery\MockInterface $conn1
         */
        $conn1 = Mockery::mock(ConnectionInterface::class);
        $msg = [
            'token' => $token,
            'room' => 'Main',
            'password' => '12345678'
        ];
        $this->service->addUser($conn, $msg);
        $this->service->addUser($conn1, $msg);
        /**
         * @var \Ratchet\ConnectionInterface&\Mockery\MockInterface $conn2
         */
        $conn2 = Mockery::mock(ConnectionInterface::class);
        $msg1 = [
            'token' => $this->token,
            'room' => 'Main',
            'password' => '12345678'
        ];
        $conn->shouldReceive('send')->once();
        $conn1->shouldReceive('send')->once();
        $this->service->addUser($conn2, $msg1);
        $conn2->shouldReceive('send')->never();
        $this->service->removeUser($conn);
    }

    public function test_adds_message(): void
    {
        Room::factory()->create();
        /**
         * @var \Ratchet\ConnectionInterface&\Mockery\MockInterface $conn
         */
        $conn = Mockery::mock(ConnectionInterface::class);
        $msg = [
            'token' => $this->token,
            'room' => 'Main',
            'password' => '12345678'
        ];
        $this->service->addUser($conn, $msg);
        $message = [
            'token' => $this->token,
            'room' => 'Main',
            'message' => 'hello there'
        ];
        $conn->shouldReceive('send')->once()->withArgs(function ($arg) {
            return str_contains($arg, '{"type":"message-broadcast","message":{"type":"message","author":{"username":"test"},"text":"hello there"');
        });
        $this->service->addMessage($message);
    }

    public function test_doesnt_add_message_and_throws_exception_if_user_didnt_enter_chatroom(): void
    {
        try {
            $message = [
                'token' => $this->token,
                'room' => 'Main',
                'message' =>  'hello there'
            ];
            $this->service->addMessage($message);
        } catch (InvalidAuthorException $e) {
            $this->assertInstanceOf(InvalidAuthorException::class, $e);
        }
    }
}