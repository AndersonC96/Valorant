<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Router;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        http_response_code(200);
    }

    protected function tearDown(): void
    {
        http_response_code(200);
        parent::tearDown();
    }

    public function testRegistersGetRouteAndExecutesHandler(): void
    {
        $router = new Router();
        $executed = false;

        $router->get('/agentes', static function () use (&$executed): void {
            $executed = true;
        });

        $router->dispatch('GET', '/agentes');

        self::assertTrue($executed, 'A rota GET registrada não foi executada.');
        self::assertSame(200, http_response_code(), 'O código HTTP não deveria mudar em uma rota válida.');
    }

    public function testReturns404ForUnregisteredRoute(): void
    {
        $router = new Router();

        ob_start();
        try {
            $router->dispatch('GET', '/rota-inexistente');
        } finally {
            $output = (string) ob_get_clean();
        }

        self::assertSame(404, http_response_code(), 'A rota inexistente deve devolver HTTP 404.');
        self::assertStringContainsString('Página não encontrada', $output);
    }
}
