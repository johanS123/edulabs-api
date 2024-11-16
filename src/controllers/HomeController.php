<?php 
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController {
    public function index(Request $request, Response $response, $args)
    {
        $response->getBody()->write('Bienvenido al home');
        return $response->withHeader('Content-Type', 'application/json');
    }
}