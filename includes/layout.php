<?php

declare(strict_types=1);

require_once __DIR__ . '/app.php';

function render_head(string $title, string $description = ''): void
{
    $description = $description ?: 'Explorador moderno de dados do universo Valorant.';
    echo '<!DOCTYPE html>';
    echo '<html lang="pt-BR">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . h($title) . '</title>';
    echo '<meta name="description" content="' . h($description) . '">';
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';    
    echo '<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">';
    echo '<link rel="icon" type="image/png" href="images/favicon.png">';
    echo '<link rel="stylesheet" href="css/normalize.css">';
    echo '<link rel="stylesheet" href="css/app.css">';
    echo '</head>';
}

function render_header(string $active = 'home'): void
{
    $featured = app_featured_collections();
    echo '<body>';
    echo '<header class="site-header">';
    echo '<div class="site-header__inner">';
    echo '<a class="brand" href="index.php" aria-label="Valorant Atlas - início">';
    echo '<span class="brand__mark">VA</span>';
    echo '<span class="brand__text">Valorant Atlas</span>';
    echo '</a>';
    echo '<button class="nav-toggle" aria-expanded="false" aria-controls="main-nav">Menu</button>';
    echo '<nav id="main-nav" class="main-nav" aria-label="Navegação principal">';
    echo '<a ' . ($active === 'home' ? 'class="is-active"' : '') . ' href="index.php">Início</a>';
    echo '<a ' . ($active === 'collections' ? 'class="is-active"' : '') . ' href="collections.php">Coleções</a>';
    echo '<a ' . ($active === 'specs' ? 'class="is-active"' : '') . ' href="specs.php">Requisitos</a>';
    foreach ($featured as $slug => $section) {
        echo '<a ' . ($active === $slug ? 'class="is-active"' : '') . ' href="collections.php?type=' . h($slug) . '">' . h($section['label']) . '</a>';
    }
    echo '</nav>';
    echo '</div>';
    echo '</header>';
    echo '<main>';
}

function render_footer(): void
{
    $year = date('Y');
    echo '</main>';
    echo '<footer class="site-footer">';
    echo '<div class="site-footer__inner">';
    echo '<p>Aplicação independente de consulta de dados, alimentada pela API pública Valorant-API.</p>';
    echo '<p>&copy; ' . h((string) $year) . ' Valorant Atlas. Valorant e marcas associadas pertencem à Riot Games.</p>';
    echo '</div>';
    echo '</footer>';
    echo '<script src="js/app.js" defer></script>';
    echo '</body>';
    echo '</html>';
}
