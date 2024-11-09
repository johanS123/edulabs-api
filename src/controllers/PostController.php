<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostController {
    public function index(Request $request, Response $response) {
        $posts = Post::all();
        $response->getBody()->write(json_encode($posts));
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
        if (empty($data['userId'])) {
            $errors['userId'] = 'El campo userId es obligatorio.';
        }
        if (empty($data['categoryId'])) {
            $errors['categoryId'] = 'El campo categoryId es obligatorio.';
        }

         // Validación de las llaves foráneas
        if (!empty($data['userId']) && !User::find($data['userId'])) {
            $errors['userId'] = 'El usuario especificado no existe.';
        }
        if (!empty($data['categoryId']) && !Category::find($data['categoryId'])) {
            $errors['categoryId'] = 'La categoría especificada no existe.';
        }

        // Si hay errores, los devuelve en la respuesta
        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
        }

        $post = Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'userId' => $data['userId'],
            'categoryId' => $data['categoryId']
        ]);

        $response->getBody()->write(json_encode($post));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function getPostsByCategory(Request $request, Response $response, array $args)
    {
        // Obtener el ID de la categoría desde los parámetros de la ruta
        $categoryId = $args['categoryId'];

        // Verificar si la categoría existe
        $category = Category::find($categoryId);
        if (!$category) {
            $response->getBody()->write(json_encode([
                'error' => 'La categoría especificada no existe.'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        // Obtener todos los posts que pertenecen a la categoría
        $posts = Post::where('categoryId', $categoryId)->get();

        // Devolver la lista de posts como respuesta JSON
        $response->getBody()->write(json_encode([
            'category' => $category->name,
            'posts' => $posts
        ]));

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
