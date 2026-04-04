<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, callable> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET ' . $this->normalize($path)] = $handler;
    }

    public function dispatch(string $method, string $uriPath): void
    {
        $key = strtoupper($method) . ' ' . $this->normalize($uriPath);

        if (!isset($this->routes[$key])) {
            http_response_code(404);
            header('Content-Type: text/html; charset=utf-8');
            echo '<h1>404</h1><p>Rota não encontrada.</p>';
            return;
        }

        call_user_func($this->routes[$key]);
    }

    private function normalize(string $path): string
    {
        $trimmed = trim($path);
        if ($trimmed === '') {
            return '/';
        }

        $normalized = '/' . trim($trimmed, '/');
        return $normalized === '//' ? '/' : $normalized;
    }
}
