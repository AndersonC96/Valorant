<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$uuid = trim((string) ($_GET['uuid'] ?? ''));
$result = ['ok' => false, 'error' => 'UUID do mapa não informado.', 'data' => []];

if ($uuid !== '') {
    $result = api_find_item_by_uuid('/maps', $uuid);
}

$map = $result['ok'] && is_array($result['data']) ? $result['data'] : [];
$name = $map['displayName'] ?? 'Mapa';
$callouts = isset($map['callouts']) && is_array($map['callouts']) ? $map['callouts'] : [];

$groupedCallouts = [];
foreach ($callouts as $callout) {
    if (!is_array($callout)) {
        continue;
    }
    $site = (string) ($callout['superRegionName'] ?? 'Sem setor');
    $groupedCallouts[$site][] = $callout;
}
ksort($groupedCallouts);

render_head('Valorant Atlas | ' . (string) $name, 'Perfil completo de mapa com callouts e detalhes táticos.');
render_header('maps');
?>
<section class="section">
    <p class="hero__eyebrow">Perfil de Mapa</p>
    <h1><?php echo h($name); ?></h1>
    <p>Leitura tático-estrutural do mapa com callouts por setor e dados técnicos da API.</p>
</section>

<?php if (!$result['ok']): ?>
    <section class="section">
        <div class="status status--error"><?php echo h($result['error']); ?></div>
        <a class="btn btn--ghost" href="collections.php?type=maps">Voltar para Mapas</a>
    </section>
<?php else: ?>
    <section class="map-detail">
        <div class="map-detail__hero card">
            <div class="map-detail__cover">
                <?php $cover = $map['splash'] ?? $map['displayIcon'] ?? null; ?>
                <?php if ($cover): ?>
                    <img src="<?php echo h((string) $cover); ?>" alt="Imagem principal do mapa <?php echo h($name); ?>">
                <?php endif; ?>
            </div>
            <div class="map-detail__summary">
                <h2><?php echo h($name); ?></h2>
                <p><?php echo h((string) ($map['narrativeDescription'] ?? $map['tacticalDescription'] ?? 'Sem descrição narrativa disponível.')); ?></p>
                <ul class="card__meta">
                    <li><b>Coordenadas:</b> <?php echo h((string) ($map['coordinates'] ?? 'Não informado')); ?></li>
                    <li><b>URL do mapa:</b> <?php echo h((string) ($map['mapUrl'] ?? 'Não informado')); ?></li>
                    <li><b>Total de callouts:</b> <?php echo h((string) count($callouts)); ?></li>
                    <li><b>UUID:</b> <?php echo h((string) ($map['uuid'] ?? 'Não informado')); ?></li>
                </ul>
            </div>
        </div>

        <div class="map-detail__grid">
            <article class="card">
                <div class="card__body">
                    <h3 class="card__title">Descrição tática</h3>
                    <p class="card__text"><?php echo h((string) ($map['tacticalDescription'] ?? 'Não informado')); ?></p>
                    <ul class="card__meta">
                        <li><b>xMultiplier:</b> <?php echo h(isset($map['xMultiplier']) ? (string) $map['xMultiplier'] : 'N/A'); ?></li>
                        <li><b>xScalarToAdd:</b> <?php echo h(isset($map['xScalarToAdd']) ? (string) $map['xScalarToAdd'] : 'N/A'); ?></li>
                        <li><b>yMultiplier:</b> <?php echo h(isset($map['yMultiplier']) ? (string) $map['yMultiplier'] : 'N/A'); ?></li>
                        <li><b>yScalarToAdd:</b> <?php echo h(isset($map['yScalarToAdd']) ? (string) $map['yScalarToAdd'] : 'N/A'); ?></li>
                    </ul>
                </div>
            </article>

            <article class="card">
                <div class="card__body">
                    <h3 class="card__title">Mini mapa</h3>
                    <?php $miniMap = $map['displayIcon'] ?? null; ?>
                    <?php if ($miniMap): ?>
                        <div class="map-detail__mini">
                            <img src="<?php echo h((string) $miniMap); ?>" alt="Mini mapa de <?php echo h($name); ?>">
                        </div>
                    <?php else: ?>
                        <p class="card__text">Este mapa não possui minimapa disponível na API.</p>
                    <?php endif; ?>
                </div>
            </article>

            <article class="card">
                <div class="card__body">
                    <h3 class="card__title">Planos de fundo</h3>
                    <ul class="card__meta">
                        <li><b>Background estilizado:</b> <?php echo h(!empty($map['stylizedBackgroundImage']) ? 'Disponível' : 'Não disponível'); ?></li>
                        <li><b>Background Premier:</b> <?php echo h(!empty($map['premierBackgroundImage']) ? 'Disponível' : 'Não disponível'); ?></li>
                        <li><b>Ícone vertical:</b> <?php echo h(!empty($map['listViewIconTall']) ? 'Disponível' : 'Não disponível'); ?></li>
                    </ul>
                </div>
            </article>
        </div>

        <article class="card">
            <div class="card__body">
                <h3 class="card__title">Callouts por setor</h3>
                <p class="card__text">Tabela de referência para leitura de comunicação de equipe, agrupada por setor principal.</p>
                <?php if (empty($groupedCallouts)): ?>
                    <p class="card__text">Nenhum callout retornado para este mapa.</p>
                <?php else: ?>
                    <div class="map-callouts">
                        <?php foreach ($groupedCallouts as $site => $items): ?>
                            <section class="map-callouts__section">
                                <h4>Setor <?php echo h($site); ?></h4>
                                <div class="table-wrap">
                                    <table class="damage-table">
                                        <thead>
                                            <tr>
                                                <th>Região</th>
                                                <th>Super-região</th>
                                                <th>X</th>
                                                <th>Y</th>
                                                <th>Z</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($items as $item): ?>
                                                <tr>
                                                    <td><?php echo h((string) ($item['regionName'] ?? 'N/A')); ?></td>
                                                    <td><?php echo h((string) ($item['superRegion'] ?? 'N/A')); ?></td>
                                                    <td><?php echo h(isset($item['location']['x']) ? (string) round((float) $item['location']['x'], 2) : 'N/A'); ?></td>
                                                    <td><?php echo h(isset($item['location']['y']) ? (string) round((float) $item['location']['y'], 2) : 'N/A'); ?></td>
                                                    <td><?php echo h(isset($item['location']['z']) ? (string) round((float) $item['location']['z'], 2) : 'N/A'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </article>

        <div class="map-detail__actions">
            <a class="btn btn--ghost" href="collections.php?type=maps">Voltar para Mapas</a>
            <a class="btn btn--primary" href="collections.php">Ir para outras coleções</a>
        </div>
    </section>
<?php endif; ?>

<?php render_footer(); ?>
