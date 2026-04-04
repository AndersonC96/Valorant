<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$uuid = trim((string) ($_GET['uuid'] ?? ''));
$result = ['ok' => false, 'error' => 'UUID do companheiro não informado.', 'data' => []];

if ($uuid !== '') {
    $result = api_find_item_by_uuid('/buddies', $uuid);
}

$buddy = $result['ok'] && is_array($result['data']) ? $result['data'] : [];
$name = $buddy['displayName'] ?? 'Companheiro';
$levels = isset($buddy['levels']) && is_array($buddy['levels']) ? $buddy['levels'] : [];

render_head('Valorant Atlas | ' . (string) $name, 'Detalhes completos de companheiro do Valorant.');
render_header('buddies');
?>
<section class="section">
    <p class="hero__eyebrow">Perfil de Companheiro</p>
    <h1><?php echo h($name); ?></h1>
    <p>Leitura detalhada do item cosmético, com níveis e metadados da API.</p>
</section>

<?php if (!$result['ok']): ?>
    <section class="section">
        <div class="status status--error"><?php echo h($result['error']); ?></div>
        <a class="btn btn--ghost" href="collections.php?type=buddies">Voltar para Companheiros</a>
    </section>
<?php else: ?>
    <section class="buddy-detail">
        <div class="buddy-detail__hero card">
            <div class="buddy-detail__media">
                <?php if (!empty($buddy['displayIcon'])): ?>
                    <img src="<?php echo h((string) $buddy['displayIcon']); ?>" alt="Companheiro <?php echo h($name); ?>">
                <?php endif; ?>
            </div>
            <div class="buddy-detail__summary">
                <h2><?php echo h($name); ?></h2>
                <ul class="card__meta">
                    <li><b>Oculto sem posse:</b> <?php echo h(!empty($buddy['isHiddenIfNotOwned']) ? 'Sim' : 'Não'); ?></li>
                    <li><b>UUID do tema:</b> <?php echo h((string) ($buddy['themeUuid'] ?? 'Não informado')); ?></li>
                    <li><b>Total de níveis:</b> <?php echo h((string) count($levels)); ?></li>
                    <li><b>UUID:</b> <?php echo h((string) ($buddy['uuid'] ?? 'Não informado')); ?></li>
                    <li><b>Caminho do asset:</b> <?php echo h((string) ($buddy['assetPath'] ?? 'Não informado')); ?></li>
                </ul>
            </div>
        </div>

        <article class="card">
            <div class="card__body">
                <h3 class="card__title">Níveis do companheiro</h3>
                <?php if (empty($levels)): ?>
                    <p class="card__text">Este companheiro não retornou níveis na API.</p>
                <?php else: ?>
                    <div class="buddy-levels">
                        <?php foreach ($levels as $level): ?>
                            <section class="buddy-level">
                                <div class="buddy-level__head">
                                    <?php if (!empty($level['displayIcon'])): ?>
                                        <img src="<?php echo h((string) $level['displayIcon']); ?>" alt="Nivel <?php echo h((string) ($level['displayName'] ?? '')); ?>">
                                    <?php endif; ?>
                                    <div>
                                        <h4><?php echo h((string) ($level['displayName'] ?? 'Nível sem nome')); ?></h4>
                                        <small>Charm Level: <?php echo h(isset($level['charmLevel']) ? (string) $level['charmLevel'] : 'N/A'); ?></small>
                                    </div>
                                </div>
                                <ul class="card__meta">
                                    <li><b>Oculto sem posse:</b> <?php echo h(!empty($level['hideIfNotOwned']) ? 'Sim' : 'Não'); ?></li>
                                    <li><b>UUID:</b> <?php echo h((string) ($level['uuid'] ?? 'Não informado')); ?></li>
                                </ul>
                            </section>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </article>

        <div class="buddy-detail__actions">
            <a class="btn btn--ghost" href="collections.php?type=buddies">Voltar para Companheiros</a>
            <a class="btn btn--primary" href="collections.php">Ir para outras coleções</a>
        </div>
    </section>
<?php endif; ?>

<?php render_footer(); ?>
