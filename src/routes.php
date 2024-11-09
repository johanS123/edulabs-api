<?php

use Slim\App;
use App\Controllers\AuthController;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;

return function (App $app) {
    $app->post('/api/register', [AuthController::class, 'register']);
    $app->post('/api/login', [AuthController::class, 'login']);

    $app->group('/api', function ($group) {
        $group->get('/posts', [PostController::class, 'index']);
        $group->post('/posts', [PostController::class, 'store']);
        $group->get('/posts/{categoryId}', [PostController::class, 'getPostsByCategory']);
        $group->delete('/posts/{id}', [UserController::class, 'delete']);
    })->add(new AuthMiddleware());

    $app->group('/api', function ($group) {
        $group->get('/users', [UserController::class, 'index']);
        $group->get('/users/{id}', [UserController::class, 'show']);
        $group->put('/users/{id}', [UserController::class, 'update']);
        $group->delete('/users/{id}', [UserController::class, 'delete']);
    })->add(new AuthMiddleware());


};
