<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ValorantApiService;

final class MapController
{
    public function __construct(private readonly ValorantApiService $service)
    {
    }

    public function index(): void
    {
        $maps = $this->service->getMaps($_ENV['API_LANGUAGE'] ?? 'pt-BR');

        require __DIR__ . '/../Views/maps/index.php';
    }
}
