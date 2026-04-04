<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class ValorantApiService
{
    private Client $http;

    public function __construct(?Client $http = null)
    {
        $baseUri = rtrim($_ENV['API_BASE_URL'] ?? 'https://valorant-api.com/v1', '/');
        $timeout = (float) ($_ENV['API_TIMEOUT'] ?? 10);
        $connectTimeout = (float) ($_ENV['API_CONNECT_TIMEOUT'] ?? 5);

        $this->http = $http ?? new Client([
            'base_uri' => $baseUri . '/',
            'timeout' => $timeout,
            'connect_timeout' => $connectTimeout,
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => $_ENV['HTTP_USER_AGENT'] ?? 'ValorantAtlas/1.0',
            ],
        ]);
    }

    /** @return array<int, array<string, mixed>> */
    public function getAgents(string $language = 'pt-BR'): array
    {
        return $this->fetchList('agents', ['language' => $language]);
    }

    /** @return array<int, array<string, mixed>> */
    public function getMaps(string $language = 'pt-BR'): array
    {
        return $this->fetchList('maps', ['language' => $language]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchCollection(string $endpoint, string $language = 'pt-BR'): array
    {
        return $this->fetchList(ltrim($endpoint, '/'), ['language' => $language]);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function fetchItemByUuid(string $endpoint, string $uuid, string $language = 'pt-BR'): ?array
    {
        $items = $this->fetchCollection($endpoint, $language);

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            if ((string) ($item['uuid'] ?? '') === $uuid) {
                return $item;
            }
        }

        return null;
    }

    /** @return array<int, array<string, mixed>> */
    private function fetchList(string $endpoint, array $query = []): array
    {
        try {
            $response = $this->http->get($endpoint, ['query' => $query]);
            $payload = json_decode((string) $response->getBody(), true);

            if (!is_array($payload) || !isset($payload['data']) || !is_array($payload['data'])) {
                return [];
            }

            $clean = [];
            foreach ($payload['data'] as $row) {
                if (is_array($row)) {
                    $clean[] = $row;
                }
            }

            return $clean;
        } catch (GuzzleException) {
            return [];
        }
    }
}
