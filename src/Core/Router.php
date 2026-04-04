<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\ErrorController;

final class Router
{
    /**
     * @var array<int, array{
     *     method: string,
     *     pattern: string,
     *     regex: string,
     *     parameterNames: array<int, string>,
     *     handler: callable
     * }>
     */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $normalizedPath = $this->normalize($path);

        $this->routes[] = [
            'method' => 'GET',
            'pattern' => $normalizedPath,
            'regex' => $this->compilePatternToRegex($normalizedPath),
            'parameterNames' => $this->extractParameterNames($normalizedPath),
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $uriPath): void
    {
        $normalizedPath = $this->normalize($uriPath);
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (!preg_match($route['regex'], $normalizedPath, $matches)) {
                continue;
            }

            $arguments = [];
            foreach ($route['parameterNames'] as $index => $parameterName) {
                $arguments[] = $matches[$index + 1] ?? null;
            }

            call_user_func_array($route['handler'], $arguments);
            return;
        }

        (new ErrorController())->notFound($uriPath);
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

    private function compilePatternToRegex(string $pattern): string
    {
        if ($pattern === '/') {
            return '~^/$~';
        }

        $segments = array_values(array_filter(explode('/', trim($pattern, '/')), static fn(string $segment): bool => $segment !== ''));
        $regexParts = [];

        foreach ($segments as $segment) {
            if ($this->isParameterSegment($segment)) {
                $regexParts[] = '([^/]+)';
                continue;
            }

            $regexParts[] = preg_quote($segment, '~');
        }

        return '~^/' . implode('/', $regexParts) . '$~';
    }

    /**
     * @return array<int, string>
     */
    private function extractParameterNames(string $pattern): array
    {
        if ($pattern === '/') {
            return [];
        }

        $segments = array_values(array_filter(explode('/', trim($pattern, '/')), static fn(string $segment): bool => $segment !== ''));
        $parameterNames = [];

        foreach ($segments as $segment) {
            if (!$this->isParameterSegment($segment)) {
                continue;
            }

            $parameterNames[] = trim($segment, '{}');
        }

        return $parameterNames;
    }

    private function isParameterSegment(string $segment): bool
    {
        return preg_match('/^\{[A-Za-z_][A-Za-z0-9_]*\}$/', $segment) === 1;
    }
}
