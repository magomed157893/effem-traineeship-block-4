<?php

use App\Controllers\UserController;

return [
    ['GET', '/api/users', [UserController::class, 'getAll']],
    ['POST', '/api/users', [UserController::class, 'create']],
    ['PUT', '/api/users/{id:\d+}', [UserController::class, 'update']],
    ['DELETE', '/api/users/{id:\d+}', [UserController::class, 'delete']],
];
