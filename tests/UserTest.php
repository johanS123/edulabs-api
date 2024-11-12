<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;

class UserTest extends TestCase
{
    protected $app;

    protected function setUp(): void
    {
        $this->app = require __DIR__ . '/bootstrap.php';
    }

    public function testGetAllUsers()
    {
        // Crea una solicitud GET a la ruta de usuarios
        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/api/users');

        $response = new Response();

        // Ejecuta la aplicación
        $response = $this->app->handle($request);

        // Verifica el código de estado
        $this->assertEquals(200, $response->getStatusCode());

        // Verifica que el cuerpo de la respuesta sea JSON
        $data = json_decode((string) $response->getBody(), true);
        $this->assertIsArray($data);
    }
}
