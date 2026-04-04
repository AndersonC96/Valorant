<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Services\ValorantApiService;

final class MapController
{
    public function __construct(private readonly ValorantApiService $service)
    {
    }

    public function index(): void
    {
        $maps = $this->service->getMaps($_ENV['API_LANGUAGE'] ?? 'pt-BR');

        View::render('maps/index', [
            'title' => 'Mapas',
            'active' => 'mapas',
            'maps' => $maps,
        ]);
    }
}
