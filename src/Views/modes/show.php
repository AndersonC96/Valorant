<?php

declare(strict_types=1);

/** @var array<string, mixed> $mode */
?>
<section class="section">
    <p class="hero__eyebrow">Detalhe de Modo</p>
    <h1><?php echo htmlspecialchars((string) ($mode['displayName'] ?? 'Modo'), ENT_QUOTES, 'UTF-8'); ?></h1>
</section>
