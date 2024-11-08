<?php

namespace App\Models;

use PDO;

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // Implementar la lógica para crear un usuario
    }

    public function read() {
        // Implementar la lógica para leer usuarios
    }

    public function update() {
        // Implementar la lógica para actualizar un usuario
    }

    public function delete() {
        // Implementar la lógica para eliminar un usuario
    }
}
