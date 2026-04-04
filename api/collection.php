<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/app.php';

$type = $_GET['type'] ?? 'agents';
$page = max(1, (int) ($_GET['page'] ?? 1));

$collection = app_collection($type);
if ($collection === null) {
    http_response_code(404);
    echo json_encode([
        'ok' => false,
        'error' => 'Colecao inexistente.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$result = api_fetch($collection['endpoint']);
if (!$result['ok']) {
    http_response_code(502);
    echo json_encode([
        'ok' => false,
        'error' => $result['error'],
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$pagination = paginate($result['data'], $page, (int) $collection['perPage']);
$cards = [];

foreach ($pagination['items'] as $item) {
    if (!is_array($item)) {
        continue;
    }
    $cards[] = card_from_item($type, $item);
}

echo json_encode([
    'ok' => true,
    'title' => $collection['label'],
    'description' => $collection['description'],
    'page' => $pagination['page'],
    'totalPages' => $pagination['totalPages'],
    'totalItems' => $pagination['total'],
    'cards' => $cards,
], JSON_UNESCAPED_UNICODE);
