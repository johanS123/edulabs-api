<?php

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware {
    private $secretKey;

    public function __construct($secretKey) {
        $this->secretKey = $secretKey;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response {
        $authHeader = $request->getHeader('Authorization');

        if ($authHeader) {
            $token = str_replace('Bearer ', '', $authHeader[0]);

            try {
                $decoded = JWT::decode($token, $this->secretKey, ['HS256']);
                $request = $request->withAttribute('user', $decoded);
            } catch (ExpiredException $e) {
                return $this->unauthorizedResponse($response, "Token ha expirado.");
            } catch (\Exception $e) {
                return $this->unauthorizedResponse($response, "Token inválido.");
            }
        } else {
            return $this->unauthorizedResponse($response, "Se requiere autenticación.");
        }

        return $handler->handle($request);
    }

    private function unauthorizedResponse(Response $response, $message) {
        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}
