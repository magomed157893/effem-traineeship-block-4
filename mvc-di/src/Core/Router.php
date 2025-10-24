<?php

namespace App\Core;

use DI\Container;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function FastRoute\simpleDispatcher;

class Router
{
    public function __construct(private Container $container) {}

    public function dispatch(Request $request): Response
    {
        $routes = array_merge(
            require_once dirname(__DIR__) . '/../routes/web.php',
            require_once dirname(__DIR__) . '/../routes/api.php'
        );

        $dispatcher = simpleDispatcher(function (RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                [$method, $path, $handler] = $route;
                $r->addRoute($method, $path, $handler);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $response = new Response('Not Found', Response::HTTP_NOT_FOUND);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $response = new Response('Method Not Allowed', Response::HTTP_METHOD_NOT_ALLOWED);
                break;
            case Dispatcher::FOUND:
                [$class, $method] = $routeInfo[1];
                $vars = $routeInfo[2] ?? [];

                $controller = $this->container->get($class);
                $callParams = array_merge($vars, ['request' => $request]);

                $response = $this->container->call([$controller, $method], $callParams);
                break;
        }

        return $response;
    }
}
