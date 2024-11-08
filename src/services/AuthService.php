<?php

namespace App\Services;

use Firebase\JWT\JWT;

class AuthService {
    private $secretKey;

    public function __construct($secretKey) {
        $this->secretKey = $secretKey;
    }

    public function generateToken($userId) {
        $payload = [
            'iat' => time(), // Tiempo de emisión
            'exp' => time() + 3600, // Tiempo de expiración (1 hora)
            'userId' => $userId
        ];

        return JWT::encode($payload, $this->secretKey);
    }
}
