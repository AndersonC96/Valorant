<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$uuid = trim((string) ($_GET['uuid'] ?? ''));
$result = ['ok' => false, 'error' => 'UUID da arma nao informado.', 'data' => []];

if ($uuid !== '') {
    $result = api_find_item_by_uuid('/weapons', $uuid);
}

$weapon = $result['ok'] && is_array($result['data']) ? $result['data'] : [];
$name = $weapon['displayName'] ?? 'Arma';
$shop = isset($weapon['shopData']) && is_array($weapon['shopData']) ? $weapon['shopData'] : [];
$stats = isset($weapon['weaponStats']) && is_array($weapon['weaponStats']) ? $weapon['weaponStats'] : [];
$damageRanges = isset($stats['damageRanges']) && is_array($stats['damageRanges']) ? $stats['damageRanges'] : [];
$adsStats = isset($stats['adsStats']) && is_array($stats['adsStats']) ? $stats['adsStats'] : [];
$skins = isset($weapon['skins']) && is_array($weapon['skins']) ? $weapon['skins'] : [];

render_head('Valorant Atlas | ' . (string) $name, 'Detalhes completos de arma no universo Valorant.');
render_header('weapons');
?>
<section class="section">
    <p class="hero__eyebrow">Perfil de Arma</p>
    <h1><?php echo h($name); ?></h1>
    <p>Painel detalhado de performance, economia e cosmeticos da arma selecionada.</p>
</section>

<?php if (!$result['ok']): ?>
    <section class="section">
        <div class="status status--error"><?php echo h($result['error']); ?></div>
        <a class="btn btn--ghost" href="collections.php?type=weapons">Voltar para Armas</a>
    </section>
<?php else: ?>
    <section class="weapon-detail">
        <div class="weapon-detail__hero card">
            <div class="weapon-detail__portrait">
                <?php if (!empty($weapon['displayIcon'])): ?>
                    <img src="<?php echo h((string) $weapon['displayIcon']); ?>" alt="Imagem da arma <?php echo h($name); ?>">
                <?php endif; ?>
            </div>
            <div class="weapon-detail__summary">
                <h2><?php echo h($name); ?></h2>
                <ul class="card__meta">
                    <li><b>Categoria tecnica:</b> <?php echo h((string) ($weapon['category'] ?? 'Nao informado')); ?></li>
                    <li><b>Categoria da loja:</b> <?php echo h((string) ($shop['categoryText'] ?? $shop['category'] ?? 'Nao informado')); ?></li>
                    <li><b>Custo:</b> <?php echo h(isset($shop['cost']) ? (string) $shop['cost'] : 'Nao informado'); ?></li>
                    <li><b>Prioridade na loja:</b> <?php echo h(isset($shop['shopOrderPriority']) ? (string) $shop['shopOrderPriority'] : 'Nao informado'); ?></li>
                    <li><b>Pode descartar:</b> <?php echo h((isset($shop['canBeTrashed']) && $shop['canBeTrashed']) ? 'Sim' : 'Nao'); ?></li>
                    <li><b>UUID:</b> <?php echo h((string) ($weapon['uuid'] ?? 'Nao informado')); ?></li>
                </ul>
            </div>
        </div>

        <div class="weapon-detail__grid">
            <article class="card">
                <div class="card__body">
                    <h3 class="card__title">Estatisticas principais</h3>
                    <ul class="card__meta">
                        <li><b>Fire rate:</b> <?php echo h(isset($stats['fireRate']) ? (string) $stats['fireRate'] : 'N/A'); ?></li>
                        <li><b>Magazine size:</b> <?php echo h(isset($stats['magazineSize']) ? (string) $stats['magazineSize'] : 'N/A'); ?></li>
                        <li><b>Reload (s):</b> <?php echo h(isset($stats['reloadTimeSeconds']) ? (string) $stats['reloadTimeSeconds'] : 'N/A'); ?></li>
                        <li><b>Equip (s):</b> <?php echo h(isset($stats['equipTimeSeconds']) ? (string) $stats['equipTimeSeconds'] : 'N/A'); ?></li>
                        <li><b>Precisao primeiro tiro:</b> <?php echo h(isset($stats['firstBulletAccuracy']) ? (string) $stats['firstBulletAccuracy'] : 'N/A'); ?></li>
                        <li><b>Multiplicador de corrida:</b> <?php echo h(isset($stats['runSpeedMultiplier']) ? (string) $stats['runSpeedMultiplier'] : 'N/A'); ?></li>
                        <li><b>Penetracao:</b> <?php echo h((string) ($stats['wallPenetration'] ?? 'N/A')); ?></li>
                        <li><b>Alt fire:</b> <?php echo h((string) ($stats['altFireType'] ?? 'N/A')); ?></li>
                    </ul>
                </div>
            </article>

            <article class="card">
                <div class="card__body">
                    <h3 class="card__title">ADS e modo alternativo</h3>
                    <?php if (empty($adsStats)): ?>
                        <p class="card__text">Esta arma nao possui estatisticas ADS detalhadas.</p>
                    <?php else: ?>
                        <ul class="card__meta">
                            <li><b>Zoom:</b> <?php echo h(isset($adsStats['zoomMultiplier']) ? (string) $adsStats['zoomMultiplier'] : 'N/A'); ?></li>
                            <li><b>Fire rate ADS:</b> <?php echo h(isset($adsStats['fireRate']) ? (string) $adsStats['fireRate'] : 'N/A'); ?></li>
                            <li><b>Run speed ADS:</b> <?php echo h(isset($adsStats['runSpeedMultiplier']) ? (string) $adsStats['runSpeedMultiplier'] : 'N/A'); ?></li>
                            <li><b>Burst count:</b> <?php echo h(isset($adsStats['burstCount']) ? (string) $adsStats['burstCount'] : 'N/A'); ?></li>
                            <li><b>Precisao ADS:</b> <?php echo h(isset($adsStats['firstBulletAccuracy']) ? (string) $adsStats['firstBulletAccuracy'] : 'N/A'); ?></li>
                        </ul>
                    <?php endif; ?>
                </div>
            </article>

            <article class="card weapon-detail__damage-card">
                <div class="card__body">
                    <h3 class="card__title">Dano por faixa de distancia</h3>
                    <?php if (empty($damageRanges)): ?>
                        <p class="card__text">Sem dados de dano por distancia para esta arma.</p>
                    <?php else: ?>
                        <div class="table-wrap">
                            <table class="damage-table">
                                <thead>
                                    <tr>
                                        <th>Faixa (m)</th>
                                        <th>Cabeca</th>
                                        <th>Corpo</th>
                                        <th>Pernas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($damageRanges as $range): ?>
                                        <tr>
                                            <td><?php echo h((string) ($range['rangeStartMeters'] ?? '?')); ?> - <?php echo h((string) ($range['rangeEndMeters'] ?? '?')); ?></td>
                                            <td><?php echo h(isset($range['headDamage']) ? (string) $range['headDamage'] : 'N/A'); ?></td>
                                            <td><?php echo h(isset($range['bodyDamage']) ? (string) $range['bodyDamage'] : 'N/A'); ?></td>
                                            <td><?php echo h(isset($range['legDamage']) ? (string) $range['legDamage'] : 'N/A'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        </div>

        <article class="card">
            <div class="card__body">
                <h3 class="card__title">Skins da arma</h3>
                <p class="card__text">A API retorna um volume alto de skins. Abaixo estao as primeiras 8 para leitura rapida com niveis e cromas.</p>
                <?php if (empty($skins)): ?>
                    <p class="card__text">Nenhuma skin registrada para esta arma.</p>
                <?php else: ?>
                    <div class="weapon-skins">
                        <?php foreach (array_slice($skins, 0, 8) as $skin): ?>
                            <section class="skin-card">
                                <div class="skin-card__head">
                                    <?php $skinIcon = $skin['displayIcon'] ?? null; ?>
                                    <?php if ($skinIcon): ?>
                                        <img src="<?php echo h((string) $skinIcon); ?>" alt="Skin <?php echo h((string) ($skin['displayName'] ?? '')); ?>">
                                    <?php endif; ?>
                                    <div>
                                        <h4><?php echo h((string) ($skin['displayName'] ?? 'Skin sem nome')); ?></h4>
                                        <small><?php echo h((string) ($skin['contentTierUuid'] ?? 'Sem tier')); ?></small>
                                    </div>
                                </div>
                                <p>Niveis: <?php echo h((string) (isset($skin['levels']) && is_array($skin['levels']) ? count($skin['levels']) : 0)); ?> | Cromas: <?php echo h((string) (isset($skin['chromas']) && is_array($skin['chromas']) ? count($skin['chromas']) : 0)); ?></p>
                            </section>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </article>

        <div class="weapon-detail__actions">
            <a class="btn btn--ghost" href="collections.php?type=weapons">Voltar para Armas</a>
            <a class="btn btn--primary" href="collections.php">Ir para outras colecoes</a>
        </div>
    </section>
<?php endif; ?>

<?php render_footer(); ?>
