<?php

use Slim\Factory\AppFactory;
use DI\Container;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Configuración de base de datos
$capsule = new Capsule();
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $_ENV['DB_HOST'],
    'database'  => $_ENV['DB_DATABASE'],
    'username'  => $_ENV['DB_USERNAME'],
    'password'  => $_ENV['DB_PASSWORD'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Configurar Eloquent para usar el acceso global
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Colocar Capsule en el contenedor para que esté disponible en el resto de la aplicación
$container->set('db', function () use ($capsule) {
    return $capsule;
});

// Cargar rutas
(require __DIR__ . '/../src/routes.php')($app);

$app->run();
