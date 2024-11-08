<?php

namespace App\Controllers;

use App\Models\Post;
use App\Services\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function create(Request $request, Response $response) {
        $data = json_decode($request->getBody());

        $post = new Post($this->db);
        $post->title = $data->title;
        $post->body = $data->body;

        if ($post->create()) {
            $response->getBody()->write(json_encode(["message" => "Publicación creada exitosamente."]));
            return $response->withStatus(201);
        }

        $response->getBody()->write(json_encode(["message" => "Error al crear la publicación."]));
        return $response->withStatus(500);
    }

    public function read(Request $request, Response $response) {
        $post = new Post($this->db);
        $posts = $post->read();

        $response->getBody()->write(json_encode($posts));
        return $response;
    }

    public function update(Request $request, Response $response, array $args) {
        $data = json_decode($request->getBody());
        $post = new Post($this->db);
        $post->id = $args['id'];
        $post->title = $data->title;
        $post->body = $data->body;

        if ($post->update()) {
            $response->getBody()->write(json_encode(["message" => "Publicación actualizada exitosamente."]));
            return $response;
        }

        $response->getBody()->write(json_encode(["message" => "Error al actualizar la publicación."]));
        return $response->withStatus(500);
    }

    public function delete(Request $request, Response $response, array $args) {
        $post = new Post($this->db);
        $post->id = $args['id'];

        if ($post->delete()) {
            $response->getBody()->write(json_encode(["message" => "Publicación eliminada exitosamente."]));
            return $response;
        }

        $response->getBody()->write(json_encode(["message" => "Error al eliminar la publicación."]));
        return $response->withStatus(500);
    }
}
