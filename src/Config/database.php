<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

class Database
{
    public static function initialize(): Capsule
    {
        // Cargar el archivo .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        
        // Crear la instancia de Capsule
        $capsule = new Capsule;

        // Configuración de la base de datos desde el archivo .env
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $_ENV['DB_HOST'],
            'database'  => $_ENV['DB_DATABASE'],
            'username'  => $_ENV['DB_USERNAME'],
            'password'  => $_ENV['DB_PASSWORD'],
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ]);

        // Establecer Capsule como global
        $capsule->setAsGlobal();

        // Iniciar Eloquent
        $capsule->bootEloquent();

        return $capsule;
    }
}
