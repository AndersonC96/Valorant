<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ValorantApiService;

final class AgentController
{
    public function __construct(private readonly ValorantApiService $service)
    {
    }

    public function index(): void
    {
        $agents = $this->service->getAgents($_ENV['API_LANGUAGE'] ?? 'pt-BR');

        require __DIR__ . '/../Views/agents/index.php';
    }
}
