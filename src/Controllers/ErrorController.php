<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

final class ErrorController
{
    public function notFound(string $path = '/'): void
    {
        http_response_code(404);

        View::render('errors/404', [
            'title' => 'Página não encontrada',
            'path' => $path,
        ]);
    }
}
