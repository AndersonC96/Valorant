<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Services\ValorantApiService;

final class AgentController
{
    public function __construct(private readonly ValorantApiService $service)
    {
    }

    public function index(): void
    {
        $agents = $this->service->getAgents($_ENV['API_LANGUAGE'] ?? 'pt-BR');

        View::render('agents/index', [
            'title' => 'Agentes',
            'active' => 'agentes',
            'agents' => $agents,
        ]);
    }
}
