<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Predis\Client;

$redis = new Client();

while (true) {
    $task = $redis->rpop('queue:emails');

    if ($task) {
        $task = json_decode($task, true);
        echo 'Отправка email: ' . $task['to'] . PHP_EOL;
    }

    sleep(1);
}
