<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Services\ValorantApiService;

final class BuddyController
{
    public function __construct(private readonly ValorantApiService $service)
    {
    }

    public function show(string $uuid): void
    {
        $buddy = $this->service->fetchItemByUuid('/buddies', $uuid, $_ENV['API_LANGUAGE'] ?? 'pt-BR');

        if ($buddy === null) {
            http_response_code(404);
            View::render('errors/404', [
                'title' => 'Companheiro não encontrado',
                'path' => '/companheiros/' . $uuid,
            ]);

            return;
        }

        View::render('buddies/show', [
            'title' => (string) ($buddy['displayName'] ?? 'Companheiro'),
            'active' => 'companheiros',
            'buddy' => $buddy,
        ]);
    }
}
