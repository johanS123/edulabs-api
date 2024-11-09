<?php

namespace App\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController {
    public function register(Request $request, Response $response) {
        $data = json_decode($request->getBody(), true);

        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $passwordHash
        ]);

        $response->getbody()->write(json_encode($user));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function login(Request $request, Response $response) {
        $data = json_decode($request->getBody(), true);

        $user = User::where('email', $data['email'])->first();

        if ($user && password_verify($data['password'], $user->password)) {
            $issuedAt = time();
            $expirationTime = $issuedAt + 3600;  // 1 hora de validez
            $payload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'sub' => $user->id  // Puedes agregar otros datos si es necesario
            ];

            $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
            $response->getBody()->write(json_encode(['token' => $token]));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode(['error' => 'Credenciales incorrectas']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}
