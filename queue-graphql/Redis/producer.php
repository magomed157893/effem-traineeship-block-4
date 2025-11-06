<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Predis\Client;

$redis = new Client();

$redis->lpush('queue:emails', json_encode([
    'to' => 'user@example.com',
    'subject' => 'Добро пожаловать!',
    'body' => 'Спасибо за регистрацию!'
]));

echo 'Задача добавлена в очередь' . PHP_EOL;
