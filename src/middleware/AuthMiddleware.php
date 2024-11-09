<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $authHeader = $request->getHeader('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader[0], $matches)) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Token no proporcionado']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $token = $matches[1];

        // Depuración: Verificar el valor del token recibido
        error_log('Token recibido: ' . $token);

        try {
            error_log('Clave secreta: ' . $_ENV['JWT_SECRET']);
            $decoded = JWT::decode($token, new key($_ENV['JWT_SECRET'], 'HS256'));
            $request = $request->withAttribute('user', $decoded);
        } catch (\Exception $e) {
            // Depuración: Verificar el error de decodificación
            error_log('Error al decodificar el token: ' . $e->getMessage());
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Token inválido']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        // Pasar la solicitud al siguiente middleware/controlador
        return $handler->handle($request);
    }
}
