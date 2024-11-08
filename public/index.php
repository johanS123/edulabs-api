<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Incluye tus rutas
(require __DIR__ . '/../src/routes/routes.php')($app);

// Ejecuta la aplicación
$app->run();
