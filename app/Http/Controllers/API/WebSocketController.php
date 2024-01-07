<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketController extends Controller implements MessageComponentInterface
{

    public function onOpen(ConnectionInterface $conn)
    {
    }

    public function onClose(ConnectionInterface $conn)
    {
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        try {
            $message = json_decode($msg, true);        
        } catch (Exception $e) {
            error_log("An exception has been catched: " . $e->getMessage() . "\n");
            $from->send(json_encode(["type" => "500"]));
        }
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        error_log("An error has occurred: " . $e->getMessage() . "\n");
        $conn->close();
    }
}