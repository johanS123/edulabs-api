<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\AuthController;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Controllers\CategoryController;
use App\Middleware\AuthMiddleware;
use Slim\Exception\HttpNotFoundException;
use App\Models\User;
use Firebase\JWT\JWT;

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

    // $app->post('/api/register', AuthController::class . ':register');
    $app->post('/api/login', function (Request $request, Response $response): Response {
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
            $response->getBody()->write(json_encode(['error' => $errors]));
            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
        }

        $user = User::where('email', $data['email'])->first();
        $dataToken = ['id' => $user->id, 'user' => $user->name];

        if ($user && password_verify($data['password'], $user->password)) {
            $issuedAt = time();
            $expirationTime = $issuedAt + 3600;  // 1 hora de validez
            $payload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'sub' => $dataToken
            ];

            $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
            $response->getBody()->write(json_encode(['token' => $token]));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode(['error' => 'Credenciales incorrectas']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    });

    // $app->group('/api', function ($group) {
    //     $group->get('/posts', [PostController::class, 'index']);
    //     $group->post('/posts', [PostController::class, 'store']);
    //     $group->put('/posts/{id}', [PostController::class, 'update']);
    //     $group->get('/posts/{categoryId}', [PostController::class, 'getPostsByCategory']);
    //     $group->delete('/posts/{id}', [UserController::class, 'delete']);
    //     // usuarios
    //     $group->get('/users', [UserController::class, 'index']);
    //     $group->get('/users/{id}', [UserController::class, 'show']);
    //     $group->put('/users/{id}', [UserController::class, 'update']);
    //     $group->delete('/users/{id}', [UserController::class, 'delete']);
    //     // categorias
    //     $group->get('/categories', [CategoryController::class, 'index']);
    
    // })->add(new AuthMiddleware());


    // $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    //     throw new HttpNotFoundException($request);
    // });
};
