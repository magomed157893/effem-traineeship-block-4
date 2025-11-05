<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use Swoole\Timer;

$server = new Server('127.0.0.1', 9501);

$server->on("start", function (Server $server) {
    Timer::tick(5000, function () {
        echo 'I am alive!' . PHP_EOL;
    });
});

$server->on('request', function (Request $request, Response $response) {
    $response->header('Content-Type', 'text/plain; charset=utf-8');
    $response->end('Hello from Swoole' . PHP_EOL);
});

$server->start();
