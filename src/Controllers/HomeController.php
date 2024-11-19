<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController {
    public function index(Request $request, Response $response) {
        $response->getBody()->write(json_encode(['message' => 'Bienvenido al home']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}