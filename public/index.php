<?php

declare(strict_types=1);

use App\Middleware\AuthMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Cargar rutas
(require __DIR__ . '/../src/routes.php')($app);

$app->run();