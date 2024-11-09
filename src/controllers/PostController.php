<?php

namespace App\Controllers;

use App\Models\Post;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostController {
    public function index(Request $request, Response $response) {
        $posts = Post::all();
        return $response->withJson($posts);
    }

    public function store(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $userId = $request->getAttribute('userId');

        $post = Post::create([
            'user_id' => $userId,
            'title' => $data['title'],
            'content' => $data['content']
        ]);

        return $response->withJson($post, 201);
    }
}
