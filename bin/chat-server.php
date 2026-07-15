<?php

require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Asia/Yangon');

use App\Infrastructure\WebSocket\ChatServer;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$port = (int) ($argv[1] ?? 8080);

echo "Starting WebSocket chat server on port $port...\n";

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    $port
);

echo "Chat server running at ws://0.0.0.0:$port\n";

$server->run();
