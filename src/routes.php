<?php

use Slim\App;
use App\Controllers\AuthController;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;

return function (App $app) {
    $app->post('/register', [AuthController::class, 'register']);
    $app->post('/login', [AuthController::class, 'login']);

    $app->group('/posts', function ($group) {
        $group->get('', [PostController::class, 'index']);
        $group->post('', [PostController::class, 'store']);
    })->add(new AuthMiddleware());

    $app->group('/users', function ($group) {
        $group->get('', [UserController::class, 'index']);
        $group->get('/{id}', [UserController::class, 'show']);
        $group->put('/{id}', [UserController::class, 'update']);
        $group->delete('/{id}', [UserController::class, 'delete']);
    })->add(new AuthMiddleware());


};
