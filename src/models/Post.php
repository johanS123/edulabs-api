<?php

namespace App\Models;

use PDO;

class Post {
    private $conn;
    private $table_name = "posts";

    public $id;
    public $title;
    public $body;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // Implementar la lógica para crear un post
    }

    public function read() {
        // Implementar la lógica para leer posts
    }

    public function update() {
        // Implementar la lógica para actualizar un post
    }

    public function delete() {
        // Implementar la lógica para eliminar un post
    }
}
