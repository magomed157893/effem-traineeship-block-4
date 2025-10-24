<?php

namespace App\Providers;

use App\Core\Database;
use DI\Container;

class DatabaseProvider
{
    public static function register(Container $container)
    {
        $container->set(Database::class, new Database());
    }
}
