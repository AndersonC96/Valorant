<?php

declare(strict_types=1);

const APP_NAME = 'Valorant Atlas';
const API_BASE_URL = 'https://valorant-api.com/v1';
const DEFAULT_LANGUAGE = 'pt-BR';

function app_collections(): array
{
    return [
        'agents' => [
            'label' => 'Agentes',
            'endpoint' => '/agents',
            'description' => 'Funções, habilidades e identidades dos agentes do Valorant.',
            'perPage' => 10,
            'featured' => true,
        ],
        'weapons' => [
            'label' => 'Armas',
            'endpoint' => '/weapons',
            'description' => 'Arsenal completo com classe, custo e dano por distância.',
            'perPage' => 10,
            'featured' => true,
        ],
        'maps' => [
            'label' => 'Mapas',
            'endpoint' => '/maps',
            'description' => 'Mapas oficiais com coordenadas e pontos de callout.',
            'perPage' => 10,
            'featured' => true,
        ],
        'gamemodes' => [
            'label' => 'Modos',
            'endpoint' => '/gamemodes',
            'description' => 'Modos de jogo e variações disponíveis.',
            'perPage' => 10,
            'featured' => true,
        ],
        'buddies' => [
            'label' => 'Companheiros',
            'endpoint' => '/buddies',
            'description' => 'Itens cosméticos de pingente para armas.',
            'perPage' => 10,
            'featured' => false,
        ],
        'bundles' => [
            'label' => 'Bundles',
            'endpoint' => '/bundles',
            'description' => 'Pacotes de coleções e skins especiais.',
            'perPage' => 10,
            'featured' => false,
        ],
        'ceremonies' => [
            'label' => 'Cerimônias',
            'endpoint' => '/ceremonies',
            'description' => 'Animações especiais de finalização e celebração.',
            'perPage' => 10,
            'featured' => false,
        ],
        'content_tiers' => [
            'label' => 'Níveis de Conteúdo',
            'endpoint' => '/contenttiers',
            'description' => 'Camadas de raridade e classificação visual.',
            'perPage' => 10,
            'featured' => false,
        ],
        'currencies' => [
            'label' => 'Moedas',
            'endpoint' => '/currencies',
            'description' => 'Moedas e recursos econômicos do jogo.',
            'perPage' => 10,
            'featured' => false,
        ],
        'events' => [
            'label' => 'Eventos',
            'endpoint' => '/events',
            'description' => 'Eventos ativos e históricos do ecossistema.',
            'perPage' => 10,
            'featured' => false,
        ],
        'gear' => [
            'label' => 'Equipamentos',
            'endpoint' => '/gear',
            'description' => 'Ferramentas táticas e utilitários de combate.',
            'perPage' => 10,
            'featured' => false,
        ],
        'player_cards' => [
            'label' => 'Player Cards',
            'endpoint' => '/playercards',
            'description' => 'Cartões de perfil com artes oficiais.',
            'perPage' => 10,
            'featured' => false,
        ],
        'player_titles' => [
            'label' => 'Títulos',
            'endpoint' => '/playertitles',
            'description' => 'Títulos desbloqueáveis para identidade do jogador.',
            'perPage' => 10,
            'featured' => false,
        ],
        'seasons' => [
            'label' => 'Temporadas',
            'endpoint' => '/seasons',
            'description' => 'Atos, episódios e janelas competitivas.',
            'perPage' => 10,
            'featured' => false,
        ],
        'sprays' => [
            'label' => 'Sprays',
            'endpoint' => '/sprays',
            'description' => 'Coleção de sprays e variações cosméticas.',
            'perPage' => 10,
            'featured' => false,
        ],
        'themes' => [
            'label' => 'Temas',
            'endpoint' => '/themes',
            'description' => 'Temas de interface e identidade visual.',
            'perPage' => 10,
            'featured' => false,
        ],
        'contracts' => [
            'label' => 'Contratos',
            'endpoint' => '/contracts',
            'description' => 'Contratos de progressão e recompensas.',
            'perPage' => 10,
            'featured' => false,
        ],
        'missions' => [
            'label' => 'Missões',
            'endpoint' => '/missions',
            'description' => 'Missões e objetivos disponíveis no ecossistema.',
            'perPage' => 10,
            'featured' => false,
        ],
        'competitive_tiers' => [
            'label' => 'Tiers Competitivos',
            'endpoint' => '/competitivetiers',
            'description' => 'Estrutura de tiers e rank competitivo.',
            'perPage' => 10,
            'featured' => false,
        ],
        'level_borders' => [
            'label' => 'Bordas de Nível',
            'endpoint' => '/levelborders',
            'description' => 'Bordas visuais de progressão de nível.',
            'perPage' => 10,
            'featured' => false,
        ],
        'gamemode_equippables' => [
            'label' => 'Equipáveis de Modos',
            'endpoint' => '/gamemodes/equippables',
            'description' => 'Itens equipáveis relacionados a modos de jogo.',
            'perPage' => 10,
            'featured' => false,
        ],
        'version' => [
            'label' => 'Versão da API',
            'endpoint' => '/version',
            'description' => 'Informações de versão do endpoint público.',
            'perPage' => 10,
            'featured' => false,
        ],
    ];
}

function app_featured_collections(): array
{
    return array_filter(app_collections(), static fn(array $item): bool => $item['featured'] === true);
}

function app_collection(string $type): ?array
{
    $all = app_collections();
    return $all[$type] ?? null;
}

function api_fetch(string $endpoint, string $language = DEFAULT_LANGUAGE): array
{
    $separator = str_contains($endpoint, '?') ? '&' : '?';
    $url = API_BASE_URL . $endpoint . $separator . 'language=' . rawurlencode($language);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 3,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ]);

    $raw = curl_exec($ch);
    $curlError = curl_error($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($raw === false) {
        return [
            'ok' => false,
            'status' => $status,
            'error' => 'Falha de rede ao consultar a API: ' . ($curlError ?: 'erro desconhecido.'),
            'data' => [],
        ];
    }

    $json = json_decode($raw, true);
    if (!is_array($json)) {
        return [
            'ok' => false,
            'status' => $status,
            'error' => 'Resposta inválida recebida da API do Valorant.',
            'data' => [],
        ];
    }

    if ($status < 200 || $status >= 300) {
        return [
            'ok' => false,
            'status' => $status,
            'error' => 'A API respondeu com status HTTP ' . $status . '.',
            'data' => [],
        ];
    }

    $data = $json['data'] ?? [];
    if (!is_array($data)) {
        $data = [$data];
    }

    return [
        'ok' => true,
        'status' => $status,
        'error' => '',
        'data' => $data,
    ];
}

function api_fetch_item(string $endpoint, string $language = DEFAULT_LANGUAGE): array
{
    $result = api_fetch($endpoint, $language);
    if (!$result['ok']) {
        return $result;
    }

    $data = $result['data'];
    if (!is_array($data)) {
        return [
            'ok' => false,
            'status' => 500,
            'error' => 'Formato de item inválido retornado pela API.',
            'data' => [],
        ];
    }

    $isList = array_keys($data) === range(0, count($data) - 1);
    $item = $isList ? ($data[0] ?? null) : $data;

    if (!is_array($item)) {
        return [
            'ok' => false,
            'status' => 404,
            'error' => 'Item não encontrado na API.',
            'data' => [],
        ];
    }

    return [
        'ok' => true,
        'status' => $result['status'],
        'error' => '',
        'data' => $item,
    ];
}

function api_find_item_by_uuid(string $endpoint, string $uuid, string $language = DEFAULT_LANGUAGE): array
{
    $result = api_fetch($endpoint, $language);
    if (!$result['ok']) {
        return $result;
    }

    foreach ($result['data'] as $item) {
        if (is_array($item) && (string) ($item['uuid'] ?? '') === $uuid) {
            return [
                'ok' => true,
                'status' => $result['status'],
                'error' => '',
                'data' => $item,
            ];
        }
    }

    return [
        'ok' => false,
        'status' => 404,
        'error' => 'Item não encontrado para o UUID informado.',
        'data' => [],
    ];
}

function safe_get(array $source, array $path, mixed $default = null): mixed
{
    $cursor = $source;
    foreach ($path as $segment) {
        if (!is_array($cursor) || !array_key_exists($segment, $cursor)) {
            return $default;
        }
        $cursor = $cursor[$segment];
    }
    return $cursor;
}

function h(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function paginate(array $items, int $page, int $perPage): array
{
    $total = count($items);
    $totalPages = max(1, (int) ceil($total / max(1, $perPage)));
    $safePage = max(1, min($page, $totalPages));
    $start = ($safePage - 1) * $perPage;

    return [
        'page' => $safePage,
        'total' => $total,
        'totalPages' => $totalPages,
        'items' => array_slice($items, $start, $perPage),
    ];
}

function card_from_item(string $type, array $item): array
{
    $base = [
        'title' => (string) ($item['displayName'] ?? $item['version'] ?? 'Sem título'),
        'image' => null,
        'description' => (string) ($item['description'] ?? ''),
        'meta' => [],
    ];

    switch ($type) {
        case 'agents':
            $base['image'] = $item['fullPortraitV2'] ?? $item['displayIcon'] ?? null;
            $base['meta'][] = ['label' => 'Função', 'value' => safe_get($item, ['role', 'displayName'], 'Sem função')];
            $base['meta'][] = ['label' => 'Nome interno', 'value' => $item['developerName'] ?? 'Não informado'];
            if (!empty($item['uuid'])) {
                $base['action'] = [
                    'label' => 'Ver perfil completo',
                    'url' => 'agent.php?uuid=' . rawurlencode((string) $item['uuid']),
                ];
            }
            break;

        case 'weapons':
            $base['image'] = $item['displayIcon'] ?? null;
            $base['meta'][] = ['label' => 'Categoria', 'value' => safe_get($item, ['shopData', 'categoryText'], 'Não informado')];
            $base['meta'][] = ['label' => 'Custo', 'value' => safe_get($item, ['shopData', 'cost'], 'N/A')];
            $base['description'] = 'Arma utilizada em confrontos táticos do universo Valorant.';
            if (!empty($item['uuid'])) {
                $base['action'] = [
                    'label' => 'Ver arma completa',
                    'url' => 'weapon.php?uuid=' . rawurlencode((string) $item['uuid']),
                ];
            }
            break;

        case 'maps':
            $base['image'] = $item['splash'] ?? $item['displayIcon'] ?? null;
            $base['meta'][] = ['label' => 'Coordenadas', 'value' => $item['coordinates'] ?? 'Não informado'];
            $callouts = isset($item['callouts']) && is_array($item['callouts']) ? count($item['callouts']) : 0;
            $base['meta'][] = ['label' => 'Callouts', 'value' => $callouts . ' regiões'];
            $base['description'] = $item['narrativeDescription'] ?? $base['description'];
            if (!empty($item['uuid'])) {
                $base['action'] = [
                    'label' => 'Ver mapa completo',
                    'url' => 'map.php?uuid=' . rawurlencode((string) $item['uuid']),
                ];
            }
            break;

        case 'gamemodes':
            $base['image'] = $item['displayIcon'] ?? null;
            $base['description'] = $item['duration'] ?? $base['description'];
            $base['meta'][] = ['label' => 'ID', 'value' => $item['uuid'] ?? 'N/A'];
            if (!empty($item['uuid'])) {
                $base['action'] = [
                    'label' => 'Ver modo completo',
                    'url' => 'mode.php?uuid=' . rawurlencode((string) $item['uuid']),
                ];
            }
            break;

        case 'buddies':
            $base['image'] = $item['displayIcon'] ?? null;
            $levels = isset($item['levels']) && is_array($item['levels']) ? count($item['levels']) : 0;
            $base['meta'][] = ['label' => 'Níveis', 'value' => (string) $levels];
            $base['meta'][] = ['label' => 'Oculto sem posse', 'value' => (!empty($item['isHiddenIfNotOwned']) ? 'Sim' : 'Não')];
            $base['description'] = 'Companheiro cosmético para personalização de armamento.';
            if (!empty($item['uuid'])) {
                $base['action'] = [
                    'label' => 'Ver companheiro completo',
                    'url' => 'buddy.php?uuid=' . rawurlencode((string) $item['uuid']),
                ];
            }
            break;

        case 'version':
            $base['title'] = 'Versão atual da API';
            $base['description'] = 'Controle de versão retornado pelo endpoint oficial.';
            $base['meta'][] = ['label' => 'Versão', 'value' => $item['version'] ?? 'Não disponível'];
            $base['action'] = [
                'label' => 'Ver detalhes da versão',
                'url' => 'entity.php?type=version',
            ];
            break;

        default:
            $base['image'] = $item['displayIcon'] ?? $item['fullRender'] ?? $item['displayIconSmall'] ?? null;
            $base['meta'][] = ['label' => 'UUID', 'value' => $item['uuid'] ?? 'Não informado'];
            if (!empty($item['uuid'])) {
                $base['action'] = [
                    'label' => 'Ver detalhes completos',
                    'url' => 'entity.php?type=' . rawurlencode($type) . '&uuid=' . rawurlencode((string) $item['uuid']),
                ];
            }
            break;
    }

    return $base;
}
