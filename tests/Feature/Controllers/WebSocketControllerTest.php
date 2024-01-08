<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Controllers\API\WebSocketController;
use App\Models\User;
use Mockery;
use App\Facades\JwToken;
use Ratchet\ConnectionInterface;
use App\Models\Room;
use App\Interfaces\IChatService;

class WebSocketControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected WebSocketController $controller;

    protected User $user;

    /**
     * @var \Ratchet\ConnectionInterface&\Mockery\MockInterface $conn
     */
    protected $conn;

    protected string $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->controller = app()->make(WebSocketController::class);
        $this->user = User::factory()->create();
        $this->conn = Mockery::mock(ConnectionInterface::class);
        $this->token = JwToken::generateJwt($this->user);
    }

    public function test_closes_socket_on_error()
    {
        $this->conn->shouldReceive('close')->once();
        $this->controller->onError($this->conn, new \Exception('Test'));
    }

    public function test_rejects_request_if_exception_was_thrown(): void
    {
        $this->mock(IChatService::class)->shouldReceive('subscribeToUpdates')->andThrow(new \Exception());
        $msg = json_encode(['type' => 'update', 'token' => $this->token]);
        $this->conn->shouldReceive('send')
            ->once()
            ->with(json_encode(["type" => "500"]));
        $controller = app(WebSocketController::class);
        $controller->onMessage($this->conn, $msg);

    }

    public function test_rejects_request_if_message_type_missing()
    {
        $msg = json_encode(['token' => $this->token]);
        $this->conn->shouldReceive('send')
            ->once()
            ->with(json_encode(["type" => "422"]));
        $this->controller->onMessage($this->conn, $msg);
    }

    public function test_rejects_request_if_token_missing()
    {
        $msg = json_encode(['type' => 'all-rooms']);
        $this->conn->shouldReceive('send')
            ->once()
            ->with(json_encode(["type" => "422"]));
        $this->controller->onMessage($this->conn, $msg);
    }


    public function test_rejects_request_if_token_invalid()
    {
        $msg = json_encode(['type' => 'all-rooms', 'token' => 'invalid token']);
        $this->conn->shouldReceive('send')
            ->once()
            ->with(json_encode(["type" => "401"]));
        $this->controller->onMessage($this->conn, $msg);
    }

    public function test_returns_list_of_all_rooms()
    {
        $msg = json_encode(['type' => 'all-rooms', 'token' => $this->token]);
        $this->conn->shouldReceive('send')
            ->once()
            ->with((json_encode(["type" => "all", 'rooms' => []])));
        $this->controller->onMessage($this->conn, $msg);
    }

    public function test_rejects_request_if_room_wasnt_found()
    {
        $msg = json_encode([
            'type' => 'user-joined',
            'room' => 'Main',
            'password' => '12345678',
            'token' => $this->token
        ]);
        $this->conn->shouldReceive('send')
            ->once()
            ->with(json_encode(["type" => "404"]));
        $this->controller->onMessage($this->conn, $msg);
    }

    public function test_rejects_request_if_password_for_chatroom_is_wrong()
    {
        Room::factory()->create();
        $msg = json_encode([
            'type' => 'user-joined',
            'room' => 'Main',
            'password' => '123456789',
            'token' => $this->token
        ]);
        $this->conn->shouldReceive('send')
            ->once()
            ->with(json_encode(["type" => "access-denied"]));
        $this->controller->onMessage($this->conn, $msg);
    }

    public function test_sends_update_on_user_join()
    {
        Room::factory()->create();
        $msg = json_encode(['type' => 'update', 'token' => $this->token]);
        $this->controller->onMessage($this->conn, $msg);
        /**
         * @var \Ratchet\ConnectionInterface&\Mockery\MockInterface $join_conn
         */
        $join_conn = Mockery::mock(ConnectionInterface::class);
        $join_msg = json_encode(['type' => 'user-joined', 'room' => 'Main', 'password' => '12345678', 'token' => $this->token]);
        $this->conn->shouldReceive('send')
            ->once()
            ->withArgs(
                function ($arg) {
                    return str_contains($arg, '"type":"update","room":{"id":1,"name":"Main","slug":"main","private":true,"messages":0,"members":1');
                }
            );
        $join_conn->shouldReceive('send')->once();
        $this->controller->onMessage($join_conn, $join_msg);
    }

    public function test_returns_room_info_when_user_joining(): void
    {
        Room::factory()->create();
        $msg = json_encode([
            'type' => 'user-joined',
            'room' => 'Main',
            'password' => '12345678',
            'token' => $this->token
        ]);
        $this->conn->shouldReceive('send')
            ->once()
            ->with(json_encode([
                "type" => 'welcome',
                'members' => ['test'],
                'messages' => []
            ]));
        $this->controller->onMessage($this->conn, $msg);
    }

    public function test_closes_connection(): void
    {
        $this->mock(IChatService::class)->shouldReceive('removeUser')->once();

        $controller = app(WebSocketController::class);
        $controller->onClose($this->conn);
    }
}