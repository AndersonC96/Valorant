<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Services\ValorantApiService;

final class WeaponController
{
    public function __construct(private readonly ValorantApiService $service)
    {
    }

    public function show(string $uuid): void
    {
        $weapon = $this->service->fetchItemByUuid('/weapons', $uuid, $_ENV['API_LANGUAGE'] ?? 'pt-BR');

        if ($weapon === null) {
            http_response_code(404);
            View::render('errors/404', [
                'title' => 'Arma não encontrada',
                'path' => '/armas/' . $uuid,
            ]);

            return;
        }

        View::render('weapons/show', [
            'title' => (string) ($weapon['displayName'] ?? 'Arma'),
            'active' => 'armas',
            'weapon' => $weapon,
        ]);
    }
}
