<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\App;
use App\Http\Controllers\API\WebSocketController;
use App\Interfaces\IChatService;

class WebSocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocket:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Websocket server initialization';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new WebSocketController(
            app()->make(IChatService::class)
        );
        $server = new App('127.0.0.1', 8090);
        $server->route('/', $controller);
        $server->run();
    }
}