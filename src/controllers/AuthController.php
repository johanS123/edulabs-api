<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\Database;
use App\Services\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController {
    private $db;
    private $authService;

    public function __construct($secretKey) {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->authService = new AuthService($secretKey);
    }

    public function login(Request $request, Response $response) {
        $data = $request->getParsedBody();

        // Validar datos
        if (empty($data['email']) || empty($data['password'])) {
            return $response->withStatus(400)->write('Usuario y contraseña son requeridos.');
        }

        // Preparar la consulta SQL para buscar al usuario
        $sql = "SELECT * FROM users WHERE email = :email";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $data['email']);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si el usuario existe y la contraseña es correcta
            if ($user && password_verify($data['password'], $user['password'])) {
                // Generar un token JWT
                $payload = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'exp' => time() + 3600 // Token expira en 1 hora
                ];
                $jwt = JWT::encode($payload, $this->secretKey);

                return $response->withStatus(200)->withJson(['token' => $jwt]);
            } else {
                return $response->withStatus(401)->write('Usuario o contraseña incorrectos.');
            }
        } catch (\Exception $e) {
            return $response->withStatus(500)->write('Error al iniciar sesión: ' . $e->getMessage());
        }
    }
}
