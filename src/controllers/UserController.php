<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;

class UserController
{
    // Obtener todos los usuarios
    public function index(Request $request, Response $response, $args)
    {
        $users = User::all();
        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Obtener un usuario por ID
    public function show(Request $request, Response $response, $args)
    {
        $userId = $args['id'];
        $user = User::find($userId);

        if (!$user) {
            $response->getBody()->write(json_encode(['error' => 'Usuario no encontrado']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($user));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Actualizar un usuario por ID
    public function update(Request $request, Response $response, $args)
    {
        $userId = $args['id'];
        $user = User::find($userId);

        if (!$user) {
            $response->getBody()->write(json_encode(['error' => 'Usuario no encontrado']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $data = $request->getParsedBody();
        
        // Actualizar los campos que se envían en el request
        $user->username = $data['username'] ?? $user->username;
        if (!empty($data['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        $user->save();

        $response->getBody()->write(json_encode($user));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Eliminar un usuario por ID
    public function delete(Request $request, Response $response, $args)
    {
        $userId = $args['id'];
        $user = User::find($userId);

        if (!$user) {
            $response->getBody()->write(json_encode(['error' => 'Usuario no encontrado']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $user->delete();
        $response->getBody()->write(json_encode(['message' => 'Usuario eliminado exitosamente']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
