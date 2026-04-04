<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

final class CollectionController
{
    public function index(): void
    {
        $this->loadLegacyHelpers();

        $allCollections = function_exists('app_collections') ? app_collections() : [];
        $requestedType = (string) ($_GET['type'] ?? 'agents');
        $type = array_key_exists($requestedType, $allCollections) ? $requestedType : 'agents';
        $initialPageSize = isset($allCollections[$type]['perPage']) ? (int) $allCollections[$type]['perPage'] : 10;

        View::render('collections/index', [
            'title' => 'Coleções',
            'active' => 'colecoes',
            'allCollections' => $allCollections,
            'type' => $type,
            'initialPageSize' => $initialPageSize,
        ]);
    }

    private function loadLegacyHelpers(): void
    {
        static $loaded = false;
        if ($loaded) {
            return;
        }

        require_once dirname(__DIR__, 2) . '/includes/app.php';
        $loaded = true;
    }
}
