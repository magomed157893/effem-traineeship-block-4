<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use React\EventLoop\Loop;
use React\Http\Browser;

use function React\Promise\all;

$loop = Loop::get();
$browser = new Browser($loop);

$urls = [
    'https://jsonplaceholder.typicode.com/posts/1',
    'https://jsonplaceholder.typicode.com/posts/2',
    'https://jsonplaceholder.typicode.com/posts/3',
    'https://jsonplaceholder.typicode.com/posts/4',
    'https://jsonplaceholder.typicode.com/posts/5'
];

$promises = array_map(function ($url) use ($browser) {
    return $browser->get($url);
}, $urls);

all($promises)->then(function ($results) {
    foreach ($results as $response) {
        echo $response->getBody() . PHP_EOL;
    }
});

echo 'Event loop started' . PHP_EOL;
$loop->run();
