<?php

declare(strict_types=1);

use App\Middleware\AuthMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Inicializar la configuración de la base de datos
require __DIR__ . '/../src/Config/database.php';
Database::initialize();

$app = AppFactory::create();

// Cargar rutas
(require __DIR__ . '/../src/routes.php')($app);

$app->run();