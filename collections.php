<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$allCollections = app_collections();
$requestedType = $_GET['type'] ?? 'agents';
$type = array_key_exists($requestedType, $allCollections) ? $requestedType : 'agents';

render_head('Valorant Atlas | Coleções', 'Explore agentes, armas, mapas e coleções do Valorant em uma interface unificada.');
render_header('collections');
?>
<section class="section">
    <p class="hero__eyebrow">Biblioteca de Dados</p>
    <h1 data-title>Coleções</h1>
    <p data-description>Consulte os dados oficiais da API do Valorant com visual consistente, navegação unificada e tratamento de falhas.</p>

    <div class="collection-controls" data-controls>
        <?php foreach ($allCollections as $slug => $collection): ?>
            <button class="chip <?php echo $slug === $type ? 'is-active' : ''; ?>" type="button" data-type="<?php echo h($slug); ?>">
                <?php echo h($collection['label']); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div data-collection-app data-initial-type="<?php echo h($type); ?>">
        <div class="status status--loading" data-status>Carregando dados da API do Valorant...</div>
        <div class="grid" data-grid aria-live="polite"></div>
        <div class="pagination" data-pagination></div>
    </div>
</section>
<?php render_footer(); ?>
