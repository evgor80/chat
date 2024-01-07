<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Controllers\API\WebSocketController;
use App\Models\User;
use Mockery;
use App\Facades\JwToken;
use Ratchet\ConnectionInterface;

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
}