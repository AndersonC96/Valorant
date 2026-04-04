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
            'description' => 'Funcoes, habilidades e identidades dos agentes do Valorant.',
            'perPage' => 10,
            'featured' => true,
        ],
        'weapons' => [
            'label' => 'Armas',
            'endpoint' => '/weapons',
            'description' => 'Arsenal completo com classe, custo e dano por distancia.',
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
            'description' => 'Modos de jogo e variacoes disponiveis.',
            'perPage' => 10,
            'featured' => true,
        ],
        'buddies' => [
            'label' => 'Companheiros',
            'endpoint' => '/buddies',
            'description' => 'Itens cosmeticos de pingente para armas.',
            'perPage' => 12,
            'featured' => false,
        ],
        'bundles' => [
            'label' => 'Bundles',
            'endpoint' => '/bundles',
            'description' => 'Pacotes de colecoes e skins especiais.',
            'perPage' => 12,
            'featured' => false,
        ],
        'ceremonies' => [
            'label' => 'Cerimonias',
            'endpoint' => '/ceremonies',
            'description' => 'Animacoes especiais de finalizacao e celebracao.',
            'perPage' => 12,
            'featured' => false,
        ],
        'content_tiers' => [
            'label' => 'Niveis de Conteudo',
            'endpoint' => '/contenttiers',
            'description' => 'Camadas de raridade e classificacao visual.',
            'perPage' => 12,
            'featured' => false,
        ],
        'currencies' => [
            'label' => 'Moedas',
            'endpoint' => '/currencies',
            'description' => 'Moedas e recursos economicos do jogo.',
            'perPage' => 12,
            'featured' => false,
        ],
        'events' => [
            'label' => 'Eventos',
            'endpoint' => '/events',
            'description' => 'Eventos ativos e historicos do ecossistema.',
            'perPage' => 12,
            'featured' => false,
        ],
        'gear' => [
            'label' => 'Equipamentos',
            'endpoint' => '/gear',
            'description' => 'Ferramentas taticas e utilitarios de combate.',
            'perPage' => 12,
            'featured' => false,
        ],
        'player_cards' => [
            'label' => 'Player Cards',
            'endpoint' => '/playercards',
            'description' => 'Cartoes de perfil com artes oficiais.',
            'perPage' => 12,
            'featured' => false,
        ],
        'player_titles' => [
            'label' => 'Titulos',
            'endpoint' => '/playertitles',
            'description' => 'Titulos desbloqueaveis para identidade do jogador.',
            'perPage' => 12,
            'featured' => false,
        ],
        'seasons' => [
            'label' => 'Temporadas',
            'endpoint' => '/seasons',
            'description' => 'Atos, episodios e janelas competitivas.',
            'perPage' => 12,
            'featured' => false,
        ],
        'sprays' => [
            'label' => 'Sprays',
            'endpoint' => '/sprays',
            'description' => 'Colecao de sprays e variacoes cosmeticas.',
            'perPage' => 12,
            'featured' => false,
        ],
        'themes' => [
            'label' => 'Temas',
            'endpoint' => '/themes',
            'description' => 'Temas de interface e identidade visual.',
            'perPage' => 12,
            'featured' => false,
        ],
        'version' => [
            'label' => 'Versao da API',
            'endpoint' => '/version',
            'description' => 'Informacoes de versao do endpoint publico.',
            'perPage' => 1,
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
            'error' => 'Resposta invalida recebida da API do Valorant.',
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
            'error' => 'Formato de item invalido retornado pela API.',
            'data' => [],
        ];
    }

    $isList = array_keys($data) === range(0, count($data) - 1);
    $item = $isList ? ($data[0] ?? null) : $data;

    if (!is_array($item)) {
        return [
            'ok' => false,
            'status' => 404,
            'error' => 'Item nao encontrado na API.',
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
        'error' => 'Item nao encontrado para o UUID informado.',
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
        'title' => (string) ($item['displayName'] ?? $item['version'] ?? 'Sem titulo'),
        'image' => null,
        'description' => (string) ($item['description'] ?? ''),
        'meta' => [],
    ];

    switch ($type) {
        case 'agents':
            $base['image'] = $item['fullPortraitV2'] ?? $item['displayIcon'] ?? null;
            $base['meta'][] = ['label' => 'Funcao', 'value' => safe_get($item, ['role', 'displayName'], 'Sem funcao')];
            $base['meta'][] = ['label' => 'Dev Name', 'value' => $item['developerName'] ?? 'Nao informado'];
            if (!empty($item['uuid'])) {
                $base['action'] = [
                    'label' => 'Ver perfil completo',
                    'url' => 'agent.php?uuid=' . rawurlencode((string) $item['uuid']),
                ];
            }
            break;

        case 'weapons':
            $base['image'] = $item['displayIcon'] ?? null;
            $base['meta'][] = ['label' => 'Categoria', 'value' => safe_get($item, ['shopData', 'categoryText'], 'Nao informado')];
            $base['meta'][] = ['label' => 'Custo', 'value' => safe_get($item, ['shopData', 'cost'], 'N/A')];
            $base['description'] = 'Arma utilizada em confrontos taticos do universo Valorant.';
            if (!empty($item['uuid'])) {
                $base['action'] = [
                    'label' => 'Ver arma completa',
                    'url' => 'weapon.php?uuid=' . rawurlencode((string) $item['uuid']),
                ];
            }
            break;

        case 'maps':
            $base['image'] = $item['splash'] ?? $item['displayIcon'] ?? null;
            $base['meta'][] = ['label' => 'Coordenadas', 'value' => $item['coordinates'] ?? 'Nao informado'];
            $callouts = isset($item['callouts']) && is_array($item['callouts']) ? count($item['callouts']) : 0;
            $base['meta'][] = ['label' => 'Callouts', 'value' => $callouts . ' regioes'];
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
            $base['meta'][] = ['label' => 'Niveis', 'value' => (string) $levels];
            $base['meta'][] = ['label' => 'Oculto sem posse', 'value' => (!empty($item['isHiddenIfNotOwned']) ? 'Sim' : 'Nao')];
            $base['description'] = 'Companheiro cosmetico para personalizacao de armamento.';
            if (!empty($item['uuid'])) {
                $base['action'] = [
                    'label' => 'Ver companheiro completo',
                    'url' => 'buddy.php?uuid=' . rawurlencode((string) $item['uuid']),
                ];
            }
            break;

        case 'version':
            $base['title'] = 'Versao atual da API';
            $base['description'] = 'Controle de versao retornado pelo endpoint oficial.';
            $base['meta'][] = ['label' => 'Version', 'value' => $item['version'] ?? 'Nao disponivel'];
            $base['action'] = [
                'label' => 'Ver detalhes da versao',
                'url' => 'entity.php?type=version',
            ];
            break;

        default:
            $base['image'] = $item['displayIcon'] ?? $item['fullRender'] ?? $item['displayIconSmall'] ?? null;
            $base['meta'][] = ['label' => 'UUID', 'value' => $item['uuid'] ?? 'Nao informado'];
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
