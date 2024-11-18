<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

// Inicializar la configuración de la base de datos
require __DIR__ . '/../src/Config/database.php';
$capsule = Database::initialize();

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

$container->set('db', function () use ($capsule) {
    return $capsule;
});

// Cargar rutas
(require __DIR__ . '/../src/routes.php')($app);

$app->run();