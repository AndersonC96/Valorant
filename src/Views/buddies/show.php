<?php

declare(strict_types=1);

/** @var array<string, mixed> $buddy */
?>
<section class="section">
    <p class="hero__eyebrow">Detalhe de Companheiro</p>
    <h1><?php echo htmlspecialchars((string) ($buddy['displayName'] ?? 'Companheiro'), ENT_QUOTES, 'UTF-8'); ?></h1>
</section>
