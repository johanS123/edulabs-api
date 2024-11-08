<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UserController; // Asegúrate de que este espacio de nombres sea correcto

return function (App $app) {
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->get('/users', [UserController::class, 'index']); // Uso del array para definir el controlador y método
        $group->post('/users', [UserController::class, 'create']);
        $group->put('/users/{id}', [UserController::class, 'update']);
        $group->delete('/users/{id}', [UserController::class, 'delete']);
    });
};

