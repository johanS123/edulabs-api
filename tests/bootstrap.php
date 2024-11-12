<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

// Cargar rutas
(require __DIR__ . '/../src/routes.php')($app);

return $app;