<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\ValorantApiService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class ValorantApiServiceTest extends TestCase
{
    public function testGetAgentsReturnsCleanedListOnSuccess(): void
    {
        $payload = [
            'status' => 200,
            'data' => [
                [
                    'uuid' => 'agent-1',
                    'displayName' => 'Gekko',
                ],
                'invalid-row',
                [
                    'uuid' => 'agent-2',
                    'displayName' => 'Fade',
                ],
            ],
        ];

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode($payload, JSON_UNESCAPED_UNICODE)),
        ]);

        $client = new Client([
            'handler' => HandlerStack::create($mock),
            'base_uri' => 'https://valorant-api.com/v1/',
        ]);

        $service = new ValorantApiService($client);
        $agents = $service->getAgents('pt-BR');

        self::assertCount(2, $agents);
        self::assertSame('agent-1', $agents[0]['uuid']);
        self::assertSame('Gekko', $agents[0]['displayName']);
        self::assertSame('agent-2', $agents[1]['uuid']);
    }

    public function testGetAgentsReturnsEmptyArrayOnHttpFailure(): void
    {
        $mock = new MockHandler([
            new RequestException(
                'Not Found',
                new Request('GET', 'agents'),
                new Response(404, ['Content-Type' => 'application/json'], json_encode(['status' => 404]))
            ),
        ]);

        $client = new Client([
            'handler' => HandlerStack::create($mock),
            'base_uri' => 'https://valorant-api.com/v1/',
        ]);

        $service = new ValorantApiService($client);
        $agents = $service->getAgents('pt-BR');

        self::assertSame([], $agents);
    }
}
