<?php

use App\Middleware\AuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/home', function (Request $request, Response $response) {
    $response->getBody()->write(json_encode(['message' => 'Bienvenido al home']));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();