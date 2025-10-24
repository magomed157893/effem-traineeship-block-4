<?php

use App\Controllers\UserController;

return [
    ['GET', '/users', [UserController::class, 'index']],
];
