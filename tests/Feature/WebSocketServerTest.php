<?php

namespace Tests\Feature;

use App\Console\Commands\WebSocketServer;
use Mockery;
use Tests\TestCase;

class WebSocketServerTest extends TestCase
{
    public function test_creates_websocket_server(): void
    {
        $server = new WebSocketServer();
        $mock = Mockery::mock('overload:Ratchet\App');
        $mock->shouldReceive("__construct")->once();
        $mock->shouldReceive("route")->once();
        $mock->shouldReceive("run")->once();
        $server->handle();
    }
}