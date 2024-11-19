<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostController {
    public function index(Request $request, Response $response) {
        // Trae todos los posts con usuario y categoría
        $posts = Post::with(['user', 'category'])->get();

        // Retorna los posts como JSON sin el campo de password del usuario
        $data = $posts->map(function ($post) {
            $user = $post->user;
            unset($user->password);
            return [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'date' => $post->date,
                'user' => $user,
                'category' => $post->category,
            ];
        });

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function store(Request $request, Response $response) {
        $data = json_decode($request->getBody(), true);
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'El campo title es obligatorio.';
        }
        if (empty($data['content'])) {
            $errors['content'] = 'El campo content es obligatorio.';
        }
        if (empty($data['user'])) {
            $errors['user'] = 'El campo userId es obligatorio.';
        }
        if (empty($data['category'])) {
            $errors['category'] = 'El campo categoryId es obligatorio.';
        }

         // Validación de las llaves foráneas
        if (!empty($data['user']) && !User::find($data['user'])) {
            $errors['user'] = 'El usuario especificado no existe.';
        }
        if (!empty($data['category']) && !Category::find($data['category'])) {
            $errors['category'] = 'La categoría especificada no existe.';
        }

        // Si hay errores, los devuelve en la respuesta
        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
        }

        $post = Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'userId' => $data['user'],
            'categoryId' => $data['category']
        ]);

        $response->getBody()->write(json_encode($post));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function getPostsByCategory(Request $request, Response $response, array $args)
    {
        // Obtener el ID de la categoría desde los parámetros de la ruta
        $categoryId = $args['categoryid'];

        // Verificar si la categoría existe
        $category = Category::find($categoryId);
        if (!$category) {
            $response->getBody()->write(json_encode([
                'error' => 'La categoría especificada no existe.'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        // Trae todos los posts con usuario y categoría
        $posts = Post::with(['user', 'category'])->get();

        // Obtener todos los posts que pertenecen a la categoría
        $posts = Post::where('categoryid', $categoryId)->get();

        // Retorna los posts como JSON sin el campo de password del usuario
        $data = $posts->map(function ($post) {
            $user = $post->user;
            unset($user->password);
            return [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'date' => $post->date,
                'user' => $user,
                'category' => $post->category,
            ];
        });

        // Devolver la lista de posts como respuesta JSON
        $response->getBody()->write(json_encode([
            'category' => $category->name,
            'posts' => $data
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function update(Request $request, Response $response, $args) {
        $postId = $args['id'];
        $post = Post::find($postId);

        if (!$post) {
            $response->getBody()->write(json_encode(['error' => 'post no encontrado']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $data = json_decode($request->getBody(), true);
        
        // Verificar si la categoría existe
        $category = Category::find($data['category']);
        if (!$category) {
            $response->getBody()->write(json_encode([
                'error' => 'La categoría especificada no existe.'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        // Actualizar los campos que se envían en el request
        $post->title = $data['title'] ?? $post->title;
        $post->content = $data['content'] ?? $post->content;
        $post->categoryId = $data['categoryId'] ?? $post->categoryId;

        $post->save();

        $response->getBody()->write(json_encode($post));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, $args)
    {
        $postId = $args['id'];
        $post = Post::find($postId);

        if (!$post) {
            $response->getBody()->write(json_encode(['error' => 'Post no encontrado']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $post->delete();
        $response->getBody()->write(json_encode(['message' => 'Post eliminado exitosamente']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}