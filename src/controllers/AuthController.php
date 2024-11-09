<?php

namespace App\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController {
    public function register(Request $request, Response $response) {
        $data = json_decode($request->getBody(), true);
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'El campo nombre es obligatorio.';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'El campo correo electrónico es obligatorio.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El formato del correo electrónico no es válido.';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'El campo contraseña es obligatorio.';
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = 'La contraseña debe tener al menos 6 caracteres.';
        }

        // Si hay errores, los devuelve en la respuesta
        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
        }

        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $passwordHash
        ]);

        $userData = $user->toArray();
        unset($userData['password']);

        $response->getbody()->write(json_encode($userData));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function login(Request $request, Response $response) {
        $data = json_decode($request->getBody(), true);
        $errors = [];

        if (empty($data['email'])) {
            $errors['email'] = 'El campo correo electrónico es obligatorio.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El formato del correo electrónico no es válido.';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'El campo contraseña es obligatorio.';
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = 'La contraseña debe tener al menos 6 caracteres.';
        }

        // Si hay errores, los devuelve en la respuesta
        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
        }

        $user = User::where('email', $data['email'])->first();

        if ($user && password_verify($data['password'], $user->password)) {
            $issuedAt = time();
            $expirationTime = $issuedAt + 3600;  // 1 hora de validez
            $payload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'sub' => $user->id
            ];

            $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
            $response->getBody()->write(json_encode(['token' => $token]));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode(['error' => 'Credenciales incorrectas']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}
