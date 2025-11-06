<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('email_queue', false, false, false, false);

$logFile = __DIR__ . '/email_log.txt';

$callback = function ($msg) use ($logFile) {
    $task = json_decode($msg->body, true);
    $line = date('Y-m-d H:i:s') . ' — Отправка email: ' . $task['to'] . PHP_EOL;

    file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
};

$channel->basic_consume('email_queue', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}
