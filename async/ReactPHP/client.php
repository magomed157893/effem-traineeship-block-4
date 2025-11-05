<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Psr\Http\Message\ResponseInterface;
use React\Http\Browser;

$browser = new Browser();

$browser
    ->get('https://jsonplaceholder.typicode.com/posts/1')
    ->then(function (ResponseInterface $response) {
        echo $response->getBody() . PHP_EOL;
    });

echo 'Запрос отправлен, ждем ответа...' . PHP_EOL;
