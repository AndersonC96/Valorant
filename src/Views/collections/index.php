<?php

declare(strict_types=1);

/** @var array<string, array<string, mixed>> $allCollections */
/** @var string $type */
/** @var int $initialPageSize */
?>
<section class="section">
    <p class="hero__eyebrow">Biblioteca de Dados</p>
    <h1 data-title>Coleções</h1>
    <p data-description>Consulte os dados oficiais da API do Valorant com navegação unificada e tratamento de falhas.</p>

    <div class="collection-controls" data-controls>
        <?php foreach ($allCollections as $slug => $collection): ?>
            <button class="chip <?php echo $slug === $type ? 'is-active' : ''; ?>" type="button" data-type="<?php echo htmlspecialchars((string) $slug, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars((string) ($collection['label'] ?? $slug), ENT_QUOTES, 'UTF-8'); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div data-collection-app data-initial-type="<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>" data-page-size="<?php echo (int) $initialPageSize; ?>">
        <div class="status status--loading" data-status>Carregando dados da coleção...</div>
        <div class="grid" data-grid aria-live="polite"></div>
        <div class="pagination" data-pagination></div>
    </div>
</section>
