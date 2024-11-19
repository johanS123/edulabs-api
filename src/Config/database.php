<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

class Database
{
    public static function initialize(): Capsule
    {
        // En producción, Render ya proporciona las variables de entorno
        if (file_exists(__DIR__ . '/../../.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }
        
        // Crear la instancia de Capsule
        $capsule = new Capsule;

        // Configuración de la base de datos desde el archivo .env
        $capsule->addConnection([
            'driver'    => 'pgsql',
            'host'      => $_ENV['DB_HOST'],
            'port'      => $_ENV['DB_PORT'],
            'database'  => $_ENV['DB_DATABASE'],
            'username'  => $_ENV['DB_USERNAME'],
            'password'  => $_ENV['DB_PASSWORD'],
            'charset'   => 'utf8',
            'prefix'    => '',
        ]);

        // Establecer Capsule como global
        $capsule->setAsGlobal();

        // Iniciar Eloquent
        $capsule->bootEloquent();

        return $capsule;
    }
}
