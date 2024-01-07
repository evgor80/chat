<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Controllers\API\WebSocketController;
use Mockery;
use Ratchet\ConnectionInterface;

class WebSocketControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected WebSocketController $controller;

    /**
     * @var \Ratchet\ConnectionInterface&\Mockery\MockInterface $conn
     */
    protected $conn;

    public function setUp(): void
    {
        parent::setUp();

        $this->controller = app()->make(WebSocketController::class);
        $this->conn = Mockery::mock(ConnectionInterface::class);
    }

    public function test_closes_socket_on_error()
    {
        $this->conn->shouldReceive('close')->once();
        $this->controller->onError($this->conn, new \Exception('Test'));
    }
}