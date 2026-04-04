<?php

declare(strict_types=1);

use App\Controllers\AgentController;
use App\Controllers\MapController;
use App\Core\Router;
use App\Services\ValorantApiService;
use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$service = new ValorantApiService();
$router = new Router();

$router->get('/', static function (): void {
    header('Location: /agentes');
    exit;
});

$router->get('/agentes', static function () use ($service): void {
    (new AgentController($service))->index();
});

$router->get('/mapas', static function () use ($service): void {
    (new MapController($service))->index();
});

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH) ?: '/';

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
if ($scriptDir !== '/' && $scriptDir !== '.') {
    if (str_starts_with($path, $scriptDir)) {
        $path = substr($path, strlen($scriptDir)) ?: '/';
    }
}

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $path);