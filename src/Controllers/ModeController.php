<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Services\ValorantApiService;

final class ModeController
{
    public function __construct(private readonly ValorantApiService $service)
    {
    }

    public function show(string $uuid): void
    {
        $mode = $this->service->fetchItemByUuid('/gamemodes', $uuid, $_ENV['API_LANGUAGE'] ?? 'pt-BR');

        if ($mode === null) {
            http_response_code(404);
            View::render('errors/404', [
                'title' => 'Modo não encontrado',
                'path' => '/modos/' . $uuid,
            ]);

            return;
        }

        View::render('modes/show', [
            'title' => (string) ($mode['displayName'] ?? 'Modo'),
            'active' => 'modos',
            'mode' => $mode,
        ]);
    }
}
