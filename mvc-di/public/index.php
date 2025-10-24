<?php

use App\Core\Router;
use App\Providers\DatabaseProvider;
use App\Providers\UserProvider;
use DI\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');

try {
    $containerBuilder = new ContainerBuilder();
    $containerBuilder->useAutowiring(true);
    $container = $containerBuilder->build();

    DatabaseProvider::register($container);
    UserProvider::register($container);

    $request = Request::createFromGlobals();
    $router = new Router($container);

    $response = $router->dispatch($request);
} catch (\Throwable $e) {
    $response = new JsonResponse(['error' => $e->getMessage()], $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
}

$response->send();
