<?php

use Slim\App;
use App\Controllers\AuthController;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Controllers\CategoryController;
use App\Middleware\AuthMiddleware;
use Slim\Exception\HttpNotFoundException;

return function (App $app) {
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    
    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);
        return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });

    $app->post('/api/register', [AuthController::class ,'register']);
    $app->post('/api/login', [AuthController::class ,'login']);

    $app->group('/api', function ($group) {
        $group->get('/posts', [PostController::class, 'index']);
        $group->post('/posts', [PostController::class, 'store']);
        $group->put('/posts/{id}', [PostController::class, 'update']);
        $group->get('/posts/{categoryId}', [PostController::class, 'getPostsByCategory']);
        $group->delete('/posts/{id}', [UserController::class, 'delete']);
        // usuarios
        $group->get('/users', [UserController::class, 'index']);
        $group->get('/users/{id}', [UserController::class, 'show']);
        $group->put('/users/{id}', [UserController::class, 'update']);
        $group->delete('/users/{id}', [UserController::class, 'delete']);
        // categorias
        $group->get('/categories', [CategoryController::class, 'index']);
    
    })->add(AuthMiddleware::class);


    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });
};
