<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use DI\Container;

use function DI\autowire;

class UserProvider
{
    public static function register(Container $container)
    {
        $container->set(UserRepositoryInterface::class, autowire(UserRepository::class));
    }
}
