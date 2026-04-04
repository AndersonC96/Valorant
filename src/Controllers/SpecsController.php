<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

final class SpecsController
{
    public function index(): void
    {
        View::render('specs/index', [
            'title' => 'Requisitos',
            'active' => 'requisitos',
        ]);
    }
}
