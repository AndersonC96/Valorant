<?php

declare(strict_types=1);

/** @var array<string, mixed> $weapon */
?>
<section class="section">
    <p class="hero__eyebrow">Perfil de Arma</p>
    <h1><?php echo htmlspecialchars((string) ($weapon['displayName'] ?? 'Arma'), ENT_QUOTES, 'UTF-8'); ?></h1>
    <p><?php echo htmlspecialchars((string) ($weapon['description'] ?? 'Detalhe técnico da arma selecionada.'), ENT_QUOTES, 'UTF-8'); ?></p>

    <div class="card">
        <div class="card__body">
            <ul class="card__meta">
                <li><b>UUID:</b> <?php echo htmlspecialchars((string) ($weapon['uuid'] ?? 'N/A'), ENT_QUOTES, 'UTF-8'); ?></li>
                <li><b>Categoria:</b> <?php echo htmlspecialchars((string) ($weapon['category'] ?? 'N/A'), ENT_QUOTES, 'UTF-8'); ?></li>
                <li><b>Preço:</b> <?php echo htmlspecialchars((string) (($weapon['shopData']['cost'] ?? 'N/A')), ENT_QUOTES, 'UTF-8'); ?></li>
            </ul>
        </div>
    </div>
</section>
