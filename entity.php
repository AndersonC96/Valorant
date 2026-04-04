<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$type = trim((string) ($_GET['type'] ?? ''));
$uuid = trim((string) ($_GET['uuid'] ?? ''));

$collection = app_collection($type);
$result = ['ok' => false, 'error' => 'Coleção inválida.', 'data' => []];

if ($collection !== null) {
    if ($type === 'version') {
        $res = api_fetch($collection['endpoint']);
        if ($res['ok']) {
            $item = $res['data'][0] ?? [];
            $result = [
                'ok' => is_array($item),
                'error' => is_array($item) ? '' : 'Resposta de versão inválida.',
                'data' => is_array($item) ? $item : [],
            ];
        } else {
            $result = ['ok' => false, 'error' => $res['error'], 'data' => []];
        }
    } elseif ($uuid !== '') {
        $res = api_find_item_by_uuid($collection['endpoint'], $uuid);
        $result = ['ok' => $res['ok'], 'error' => $res['error'], 'data' => is_array($res['data']) ? $res['data'] : []];
    } else {
        $result = ['ok' => false, 'error' => 'UUID não informado para esta coleção.', 'data' => []];
    }
}

$item = $result['ok'] ? $result['data'] : [];
$title = $result['ok'] ? (string) ($item['displayName'] ?? $collection['label'] ?? 'Item') : 'Detalhe';

function detail_image(array $item): ?string
{
    $candidates = [
        $item['displayIcon'] ?? null,
        $item['displayIcon2'] ?? null,
        $item['listViewIcon'] ?? null,
        $item['listViewIconTall'] ?? null,
        $item['fullRender'] ?? null,
        $item['splash'] ?? null,
        $item['verticalPromoImage'] ?? null,
        $item['background'] ?? null,
    ];

    foreach ($candidates as $img) {
        if (is_string($img) && $img !== '') {
            return $img;
        }
    }

    return null;
}

function detail_scalar_rows(array $item): array
{
    $rows = [];
    foreach ($item as $key => $value) {
        if (is_scalar($value) || $value === null) {
            $rows[] = [
                'label' => (string) $key,
                'value' => $value === null ? 'null' : (string) $value,
            ];
        }
    }
    return $rows;
}

function detail_arrays_rows(array $item): array
{
    $rows = [];
    foreach ($item as $key => $value) {
        if (is_array($value)) {
            $isList = array_keys($value) === range(0, count($value) - 1);
            $rows[] = [
                'label' => (string) $key,
                'kind' => $isList ? 'lista' : 'objeto',
                'count' => (string) count($value),
            ];
        }
    }
    return $rows;
}

render_head('Valorant Atlas | ' . $title, 'Visualização única de item para coleções do Valorant Atlas.');
render_header('collections');
?>
<section class="section">
    <p class="hero__eyebrow">Visualização Única</p>
    <h1><?php echo h($title); ?></h1>
    <p><?php echo h((string) ($collection['description'] ?? 'Detalhes completos do item selecionado.')); ?></p>
</section>

<?php if (!$result['ok']): ?>
    <section class="section">
        <div class="status status--error"><?php echo h($result['error']); ?></div>
        <a class="btn btn--ghost" href="collections.php?type=<?php echo h($type ?: 'agents'); ?>">Voltar para coleção</a>
    </section>
<?php else: ?>
    <?php $image = detail_image($item); ?>
    <?php $scalars = detail_scalar_rows($item); ?>
    <?php $arrays = detail_arrays_rows($item); ?>

    <section class="entity-detail">
        <div class="entity-detail__hero card">
            <div class="entity-detail__media">
                <?php if ($image !== null): ?>
                    <img src="<?php echo h($image); ?>" alt="Imagem de <?php echo h($title); ?>">
                <?php else: ?>
                    <div class="entity-detail__empty">Sem imagem disponível</div>
                <?php endif; ?>
            </div>
            <div class="entity-detail__summary">
                <h2><?php echo h($title); ?></h2>
                <ul class="card__meta">
                    <li><b>Coleção:</b> <?php echo h((string) ($collection['label'] ?? $type)); ?></li>
                    <li><b>UUID:</b> <?php echo h((string) ($item['uuid'] ?? 'Não informado')); ?></li>
                    <li><b>Caminho do asset:</b> <?php echo h((string) ($item['assetPath'] ?? 'Não informado')); ?></li>
                    <li><b>Campos escalares:</b> <?php echo h((string) count($scalars)); ?></li>
                    <li><b>Campos complexos:</b> <?php echo h((string) count($arrays)); ?></li>
                </ul>
            </div>
        </div>

        <div class="entity-detail__grid">
            <article class="card">
                <div class="card__body">
                    <h3 class="card__title">Campos diretos</h3>
                    <?php if (empty($scalars)): ?>
                        <p class="card__text">Sem campos escalares para exibir.</p>
                    <?php else: ?>
                        <div class="table-wrap">
                            <table class="damage-table">
                                <thead>
                                    <tr>
                                        <th>Campo</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($scalars as $row): ?>
                                        <tr>
                                            <td><?php echo h($row['label']); ?></td>
                                            <td><?php echo h($row['value']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </article>

            <article class="card">
                <div class="card__body">
                    <h3 class="card__title">Estruturas internas</h3>
                    <?php if (empty($arrays)): ?>
                        <p class="card__text">Sem objetos/listas internas neste item.</p>
                    <?php else: ?>
                        <ul class="mode-overrides">
                            <?php foreach ($arrays as $row): ?>
                                <li>
                                    <b><?php echo h($row['label']); ?></b>
                                    <span><?php echo h($row['kind'] . ' (' . $row['count'] . ')'); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </article>
        </div>

        <article class="card">
            <div class="card__body">
                <h3 class="card__title">JSON bruto do item</h3>
                <p class="card__text">Visualização técnica completa para depuração e exploração da API.</p>
                <pre class="entity-json"><?php echo h((string) json_encode($item, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)); ?></pre>
            </div>
        </article>

        <div class="entity-detail__actions">
            <a class="btn btn--ghost" href="collections.php?type=<?php echo h($type); ?>">Voltar para coleção</a>
            <a class="btn btn--primary" href="collections.php">Ir para outras coleções</a>
        </div>
    </section>
<?php endif; ?>

<?php render_footer(); ?>
