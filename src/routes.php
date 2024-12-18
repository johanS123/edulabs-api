<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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

    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Hello world!");
        return $response;
    });

    $app->get('/home', \App\Controllers\HomeController::class . ':index');
    $app->post('/api/register', \App\Controllers\AuthController::class . ':register');
    $app->post('/api/login', \App\Controllers\AuthController::class . ':login');

    $app->group('/api', function ($group) {
        // posts
        $group->get('/posts', \App\Controllers\PostController::class . ':index');
        $group->post('/posts', \App\Controllers\PostController::class . ':store');
        $group->put('/posts/{id}', \App\Controllers\PostController::class . ':update');
        $group->get('/posts/{categoryid}', \App\Controllers\PostController::class . ':getPostsByCategory');
        $group->delete('/posts/{id}', \App\Controllers\PostController::class . ':delete');
         // usuarios
         $group->get('/users', \App\Controllers\UserController::class . ':index');
         $group->get('/users/{id}', \App\Controllers\UserController::class . ':show');
         $group->put('/users/{id}', \App\Controllers\UserController::class . ':update');
         $group->delete('/users/{id}', \App\Controllers\UserController::class . ':delete');
         // categorias
         $group->get('/categories', \App\Controllers\CategoryController::class . ':index');

    });

    $app->any('/{path:.*}', function ($request, $response) {
        $response->getBody()->write('Not Found');
        return $response->withHeader('Content-Type', 'text/plain')->withStatus(404);
    });

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });
};