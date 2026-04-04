<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Services\ValorantApiService;

final class CollectionApiController
{
    private const DEFAULT_LANGUAGE = 'pt-BR';
    private const DEFAULT_PAGE_SIZE = 10;

    /**
     * @var array<string, array{label: string, endpoint: string, description: string, perPage: int}>
     */
    private array $collections = [
        'agents' => [
            'label' => 'Agentes',
            'endpoint' => '/agents',
            'description' => 'Funções, habilidades e identidades dos agentes do Valorant.',
            'perPage' => 10,
        ],
        'weapons' => [
            'label' => 'Armas',
            'endpoint' => '/weapons',
            'description' => 'Arsenal completo com classe, custo e dano por distância.',
            'perPage' => 10,
        ],
        'maps' => [
            'label' => 'Mapas',
            'endpoint' => '/maps',
            'description' => 'Mapas oficiais com coordenadas e pontos de callout.',
            'perPage' => 10,
        ],
        'gamemodes' => [
            'label' => 'Modos',
            'endpoint' => '/gamemodes',
            'description' => 'Modos de jogo e variações disponíveis.',
            'perPage' => 10,
        ],
        'buddies' => [
            'label' => 'Companheiros',
            'endpoint' => '/buddies',
            'description' => 'Itens cosméticos de pingente para armas.',
            'perPage' => 10,
        ],
        'bundles' => [
            'label' => 'Bundles',
            'endpoint' => '/bundles',
            'description' => 'Pacotes de coleções e skins especiais.',
            'perPage' => 10,
        ],
        'ceremonies' => [
            'label' => 'Cerimônias',
            'endpoint' => '/ceremonies',
            'description' => 'Animações especiais de finalização e celebração.',
            'perPage' => 10,
        ],
        'content_tiers' => [
            'label' => 'Níveis de Conteúdo',
            'endpoint' => '/contenttiers',
            'description' => 'Camadas de raridade e classificação visual.',
            'perPage' => 10,
        ],
        'currencies' => [
            'label' => 'Moedas',
            'endpoint' => '/currencies',
            'description' => 'Moedas e recursos econômicos do jogo.',
            'perPage' => 10,
        ],
        'events' => [
            'label' => 'Eventos',
            'endpoint' => '/events',
            'description' => 'Eventos ativos e históricos do ecossistema.',
            'perPage' => 10,
        ],
        'gear' => [
            'label' => 'Equipamentos',
            'endpoint' => '/gear',
            'description' => 'Ferramentas táticas e utilitários de combate.',
            'perPage' => 10,
        ],
        'player_cards' => [
            'label' => 'Player Cards',
            'endpoint' => '/playercards',
            'description' => 'Cartões de perfil com artes oficiais.',
            'perPage' => 10,
        ],
        'player_titles' => [
            'label' => 'Títulos',
            'endpoint' => '/playertitles',
            'description' => 'Títulos desbloqueáveis para identidade do jogador.',
            'perPage' => 10,
        ],
        'seasons' => [
            'label' => 'Temporadas',
            'endpoint' => '/seasons',
            'description' => 'Atos, episódios e janelas competitivas.',
            'perPage' => 10,
        ],
        'sprays' => [
            'label' => 'Sprays',
            'endpoint' => '/sprays',
            'description' => 'Coleção de sprays e variações cosméticas.',
            'perPage' => 10,
        ],
        'themes' => [
            'label' => 'Temas',
            'endpoint' => '/themes',
            'description' => 'Temas de interface e identidade visual.',
            'perPage' => 10,
        ],
        'contracts' => [
            'label' => 'Contratos',
            'endpoint' => '/contracts',
            'description' => 'Contratos de progressão e recompensas.',
            'perPage' => 10,
        ],
        'missions' => [
            'label' => 'Missões',
            'endpoint' => '/missions',
            'description' => 'Missões e objetivos disponíveis no ecossistema.',
            'perPage' => 10,
        ],
        'competitive_tiers' => [
            'label' => 'Tiers Competitivos',
            'endpoint' => '/competitivetiers',
            'description' => 'Estrutura de tiers e rank competitivo.',
            'perPage' => 10,
        ],
        'level_borders' => [
            'label' => 'Bordas de Nível',
            'endpoint' => '/levelborders',
            'description' => 'Bordas visuais de progressão de nível.',
            'perPage' => 10,
        ],
        'gamemode_equippables' => [
            'label' => 'Equipáveis de Modos',
            'endpoint' => '/gamemodes/equippables',
            'description' => 'Itens equipáveis relacionados a modos de jogo.',
            'perPage' => 10,
        ],
        'version' => [
            'label' => 'Versão da API',
            'endpoint' => '/version',
            'description' => 'Informações de versão do endpoint público.',
            'perPage' => 10,
        ],
    ];

    public function __construct(private readonly ValorantApiService $service)
    {
    }

    public function index(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $type = $this->normalizeType((string) ($_GET['type'] ?? 'agents'));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $language = (string) ($_GET['language'] ?? self::DEFAULT_LANGUAGE);

        $collection = $this->collections[$type] ?? null;
        if ($collection === null) {
            http_response_code(404);
            echo json_encode([
                'ok' => false,
                'error' => 'Coleção inexistente.',
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $items = $this->service->fetchCollection($collection['endpoint'], $language);
        $pagination = $this->paginate($items, $page, $collection['perPage'] ?? self::DEFAULT_PAGE_SIZE);

        $cards = [];
        foreach ($pagination['items'] as $item) {
            if (!is_array($item)) {
                continue;
            }

            $cards[] = $this->buildCard($type, $item);
        }

        echo json_encode([
            'ok' => true,
            'title' => $collection['label'],
            'description' => $collection['description'],
            'type' => $type,
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
            'totalItems' => $pagination['total'],
            'cards' => $cards,
        ], JSON_UNESCAPED_UNICODE);
    }

    private function normalizeType(string $type): string
    {
        $type = trim($type);

        return $type === '' ? 'agents' : $type;
    }

    /**
     * @param array<int, mixed> $items
     * @return array{items: array<int, mixed>, page: int, totalPages: int, total: int}
     */
    private function paginate(array $items, int $page, int $perPage): array
    {
        $total = count($items);
        $totalPages = max(1, (int) ceil($total / max(1, $perPage)));
        $page = min(max(1, $page), $totalPages);
        $offset = ($page - 1) * $perPage;

        return [
            'items' => array_slice($items, $offset, $perPage),
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ];
    }

    /**
     * @param array<string, mixed> $item
     * @return array<string, mixed>
     */
    private function buildCard(string $type, array $item): array
    {
        return match ($type) {
            'agents' => [
                'uuid' => (string) ($item['uuid'] ?? ''),
                'title' => (string) ($item['displayName'] ?? 'Agente'),
                'description' => (string) ($item['description'] ?? ''),
                'image' => (string) ($item['displayIcon'] ?? ''),
            ],
            'weapons' => [
                'uuid' => (string) ($item['uuid'] ?? ''),
                'title' => (string) ($item['displayName'] ?? 'Arma'),
                'description' => (string) ($item['category'] ?? ''),
                'image' => (string) ($item['displayIcon'] ?? ''),
            ],
            'maps' => [
                'uuid' => (string) ($item['uuid'] ?? ''),
                'title' => (string) ($item['displayName'] ?? 'Mapa'),
                'description' => (string) ($item['tacticalDescription'] ?? ''),
                'image' => (string) ($item['displayIcon'] ?? ''),
            ],
            default => [
                'uuid' => (string) ($item['uuid'] ?? ''),
                'title' => (string) ($item['displayName'] ?? $item['name'] ?? 'Item'),
                'description' => (string) ($item['description'] ?? ''),
                'image' => (string) ($item['displayIcon'] ?? ''),
            ],
        };
    }
}
