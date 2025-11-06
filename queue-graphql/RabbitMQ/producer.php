<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('email_queue', false, false, false, false);

$msg = new AMQPMessage(json_encode([
    'to' => 'user@example.com',
    'subject' => 'Привет!',
    'body' => 'Спасибо за регистрацию!'
]));

$channel->basic_publish($msg, '', 'email_queue');

echo 'Задача добавлена в RabbitMQ' . PHP_EOL;

$channel->close();
$connection->close();
