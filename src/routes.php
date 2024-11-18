<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (App $app) {
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
        $group->get('/posts/{categoryId}', \App\Controllers\PostController::class . ':getPostsByCategory');
        $group->delete('/posts/{id}', \App\Controllers\PostController::class . ':delete');
    });

    $app->any('/{path:.*}', function (Request $request, Response $response) {
        $data = ['error' => 'Ruta no encontrada'];
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(404)
            ->getBody()->write(json_encode($data));
    });
};