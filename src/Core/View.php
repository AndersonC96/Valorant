<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    /**
     * @param array<string, mixed> $data
     */
    public static function render(string $view, array $data = [], string $layout = 'layouts/base'): void
    {
        $viewFile = self::resolveViewFile($view);
        $layoutFile = self::resolveViewFile($layout);

        if (!is_file($viewFile)) {
            throw new \RuntimeException('View não encontrada: ' . $view);
        }

        if (!is_file($layoutFile)) {
            throw new \RuntimeException('Layout não encontrado: ' . $layout);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = (string) ob_get_clean();

        require $layoutFile;
    }

    private static function resolveViewFile(string $view): string
    {
        return dirname(__DIR__) . '/Views/' . str_replace('..', '', $view) . '.php';
    }
}
