<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\Database;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index(Request $request, Response $response, $args)
    {
        try {
            // Usa \PDO para acceder a la clase global
            $stmt = $this->db->query("SELECT * FROM users"); // Cambia 'users' por el nombre de tu tabla
            $users = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Obtiene todos los usuarios

            $response->getBody()->write(json_encode($users)); // Devuelve la lista de usuarios
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function create(Request $request, Response $response) {
        // Obtiene los datos del cuerpo de la solicitud
        $data = json_decode($request->getBody(), true);
        
        // Validación de datos
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            $response->getBody()->write(json_encode(['error' => 'Todos los campos son obligatorios']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Prepara la consulta para insertar un nuevo usuario
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

        // Asigna los parámetros
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        // Asegúrate de que la contraseña esté correctamente encriptada
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);

        try {
            // Ejecuta la consulta
            if ($stmt->execute()) {
                $response->getBody()->write(json_encode(['message' => 'Usuario creado exitosamente']));
                return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
            } else {
                return $response->withStatus(500)->withHeader('Content-Type', 'application/json')
                    ->getBody()->write(json_encode(['error' => 'Error al crear el usuario']));
            }
        } catch (\PDOException $e) {
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json')
                ->getBody()->write(json_encode(['error' => $e->getMessage()]));
        }
    }

    public function read(Request $request, Response $response) {
        $user = new User($this->db);
        $users = $user->read();

        $response->getBody()->write(json_encode($users));
        return $response;
    }

    public function update(Request $request, Response $response, array $args) {
        // Obtener el ID del usuario desde los argumentos
        $userId = $args['id'];
        

        // Obtener los datos del cuerpo de la solicitud
        $rawBody = $request->getBody()->getContents();
        $data = json_decode($rawBody, true);

        // Validar que se reciban todos los datos necesarios
        if (empty($data['name']) || empty($data['email'])) {
            $response->getBody()->write(json_encode(['error' => 'Todos los campos son obligatorios']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Preparar la consulta de actualización
        $query = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $this->db->prepare($query);

        // // Asignar valores a los parámetros
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':id', $userId, \PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se actualizó algún registro
            if ($stmt->rowCount() > 0) {
                $response->getBody()->write("Usuario con ID {$userId} actualizado con éxito.");
                return $response->withStatus(200);
            } else {
                return $response->withStatus(404)->write('Usuario no encontrado o no se realizaron cambios.');
            }
        } else {
            return $response->withStatus(500)->write('Error al actualizar el usuario.');
        }
    }

    public function delete(Request $request, Response $response, array $args) {
        // Obtener el ID del usuario desde los argumentos de la URL
        $userId = $args['id'];

        // Preparar la consulta SQL para eliminar al usuario
        $sql = "DELETE FROM users WHERE id = :id";

        try {
            // Preparar la declaración
            $stmt = $this->db->prepare($sql);

            // Vincular el parámetro
            $stmt->bindParam(':id', $userId, \PDO::PARAM_INT);

            // Ejecutar la declaración
            if ($stmt->execute()) {
                // Si se eliminó correctamente, devolver respuesta 204 No Content
                return $response->withStatus(204);
            } else {
                // Si no se encontró el usuario, devolver 404 Not Found
                return $response->withStatus(404)->write('Usuario no encontrado.');
            }
        } catch (\Exception $e) {
            // Manejo de errores
            return $response->withStatus(500)->write('Error en la base de datos: ' . $e->getMessage());
        }

        $response->getBody()->write(json_encode($userId)); // Devuelve la lista de usuarios
            return $response->withHeader('Content-Type', 'application/json');
    }
}
