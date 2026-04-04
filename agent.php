<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$uuid = trim((string) ($_GET['uuid'] ?? ''));
$result = ['ok' => false, 'error' => 'UUID do agente nao informado.', 'data' => []];

if ($uuid !== '') {
    $result = api_fetch_item('/agents/' . rawurlencode($uuid));
}

$agent = $result['ok'] && is_array($result['data']) ? $result['data'] : [];
$name = $agent['displayName'] ?? 'Agente';

render_head('Valorant Atlas | ' . (string) $name, 'Perfil completo de agente do Valorant com habilidades e metadados.');
render_header('agents');
?>
<section class="section">
    <p class="hero__eyebrow">Perfil de Agente</p>
    <h1><?php echo h($name); ?></h1>
    <p>Visao detalhada do agente selecionado com todas as informacoes publicadas pela API.</p>
</section>

<?php if (!$result['ok']): ?>
    <section class="section">
        <div class="status status--error"><?php echo h($result['error']); ?></div>
        <a class="btn btn--ghost" href="collections.php?type=agents">Voltar para Agentes</a>
    </section>
<?php else: ?>
    <section class="agent-detail">
        <div class="agent-detail__hero card">
            <div class="agent-detail__portrait">
                <?php $portrait = $agent['fullPortraitV2'] ?? $agent['fullPortrait'] ?? $agent['bustPortrait'] ?? $agent['displayIcon'] ?? null; ?>
                <?php if ($portrait): ?>
                    <img src="<?php echo h($portrait); ?>" alt="Retrato de <?php echo h($name); ?>">
                <?php endif; ?>
            </div>
            <div class="agent-detail__summary">
                <h2><?php echo h($name); ?></h2>
                <p><?php echo h($agent['description'] ?? 'Sem descricao disponivel.'); ?></p>
                <ul class="card__meta">
                    <li><b>Funcao:</b> <?php echo h(safe_get($agent, ['role', 'displayName'], 'Nao informado')); ?></li>
                    <li><b>Dev Name:</b> <?php echo h($agent['developerName'] ?? 'Nao informado'); ?></li>
                    <li><b>Personagem jogavel:</b> <?php echo h(($agent['isPlayableCharacter'] ?? false) ? 'Sim' : 'Nao'); ?></li>
                    <li><b>Disponivel para teste:</b> <?php echo h(($agent['isAvailableForTest'] ?? false) ? 'Sim' : 'Nao'); ?></li>
                    <li><b>Base content:</b> <?php echo h(($agent['isBaseContent'] ?? false) ? 'Sim' : 'Nao'); ?></li>
                    <li><b>UUID:</b> <?php echo h($agent['uuid'] ?? 'Nao informado'); ?></li>
                </ul>
            </div>
        </div>

        <div class="agent-detail__grid">
            <article class="card">
                <div class="card__body">
                    <h3 class="card__title">Classe tatica</h3>
                    <p class="card__text"><?php echo h(safe_get($agent, ['role', 'description'], 'Sem descricao de funcao.')); ?></p>
                    <?php $roleIcon = safe_get($agent, ['role', 'displayIcon'], ''); ?>
                    <?php if ($roleIcon !== ''): ?>
                        <div class="agent-detail__icon-row">
                            <img src="<?php echo h($roleIcon); ?>" alt="Icone da funcao do agente">
                        </div>
                    <?php endif; ?>
                </div>
            </article>

            <article class="card">
                <div class="card__body">
                    <h3 class="card__title">Habilidades</h3>
                    <?php $abilities = isset($agent['abilities']) && is_array($agent['abilities']) ? $agent['abilities'] : []; ?>
                    <?php if (empty($abilities)): ?>
                        <p class="card__text">Sem habilidades registradas.</p>
                    <?php else: ?>
                        <div class="agent-abilities">
                            <?php foreach ($abilities as $ability): ?>
                                <section class="ability">
                                    <header class="ability__head">
                                        <?php if (!empty($ability['displayIcon'])): ?>
                                            <img src="<?php echo h($ability['displayIcon']); ?>" alt="Icone habilidade <?php echo h($ability['displayName'] ?? ''); ?>">
                                        <?php endif; ?>
                                        <div>
                                            <h4><?php echo h($ability['displayName'] ?? 'Habilidade sem nome'); ?></h4>
                                            <small><?php echo h($ability['slot'] ?? 'Slot nao informado'); ?></small>
                                        </div>
                                    </header>
                                    <p><?php echo h($ability['description'] ?? 'Sem descricao.'); ?></p>
                                </section>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </article>

            <article class="card">
                <div class="card__body">
                    <h3 class="card__title">Dados visuais e audio</h3>
                    <ul class="card__meta">
                        <li><b>Icone:</b> <?php echo h(!empty($agent['displayIcon']) ? 'Disponivel' : 'Nao disponivel'); ?></li>
                        <li><b>Splash:</b> <?php echo h(!empty($agent['background']) ? 'Disponivel' : 'Nao disponivel'); ?></li>
                        <li><b>Voice line:</b> <?php echo h(!empty(safe_get($agent, ['voiceLine', 'mediaList', 0, 'wave'])) ? 'Disponivel' : 'Nao disponivel'); ?></li>
                        <li><b>Background gradients:</b> <?php echo h(isset($agent['backgroundGradientColors']) && is_array($agent['backgroundGradientColors']) ? (string) count($agent['backgroundGradientColors']) : '0'); ?></li>
                        <li><b>Tags:</b> <?php echo h(isset($agent['characterTags']) && is_array($agent['characterTags']) ? (string) count($agent['characterTags']) : '0'); ?></li>
                    </ul>
                    <?php $voice = safe_get($agent, ['voiceLine', 'mediaList', 0, 'wave'], ''); ?>
                    <?php if ($voice !== ''): ?>
                        <audio controls preload="none" src="<?php echo h($voice); ?>"></audio>
                    <?php endif; ?>
                </div>
            </article>
        </div>

        <div class="agent-detail__actions">
            <a class="btn btn--ghost" href="collections.php?type=agents">Voltar para Agentes</a>
            <a class="btn btn--primary" href="collections.php">Ir para outras colecoes</a>
        </div>
    </section>
<?php endif; ?>

<?php render_footer(); ?>
