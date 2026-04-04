<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$uuid = trim((string) ($_GET['uuid'] ?? ''));
$result = ['ok' => false, 'error' => 'UUID do modo não informado.', 'data' => []];

if ($uuid !== '') {
    $result = api_find_item_by_uuid('/gamemodes', $uuid);
}

$mode = $result['ok'] && is_array($result['data']) ? $result['data'] : [];
$name = $mode['displayName'] ?? 'Modo';
$features = isset($mode['gameFeatureOverrides']) && is_array($mode['gameFeatureOverrides']) ? $mode['gameFeatureOverrides'] : [];
$rules = isset($mode['gameRuleBoolOverrides']) && is_array($mode['gameRuleBoolOverrides']) ? $mode['gameRuleBoolOverrides'] : [];

render_head('Valorant Atlas | ' . (string) $name, 'Detalhes completos do modo de jogo no universo Valorant.');
render_header('gamemodes');
?>
<section class="section">
    <p class="hero__eyebrow">Perfil de Modo</p>
    <h1><?php echo h($name); ?></h1>
    <p>Visão detalhada das configurações de partida, regras e comportamento deste modo.</p>
</section>

<?php if (!$result['ok']): ?>
    <section class="section">
        <div class="status status--error"><?php echo h($result['error']); ?></div>
        <a class="btn btn--ghost" href="collections.php?type=gamemodes">Voltar para Modos</a>
    </section>
<?php else: ?>
    <section class="mode-detail">
        <div class="mode-detail__hero card">
            <div class="mode-detail__media">
                <?php $icon = $mode['listViewIconTall'] ?? $mode['displayIcon'] ?? null; ?>
                <?php if ($icon): ?>
                    <img src="<?php echo h((string) $icon); ?>" alt="Imagem do modo <?php echo h($name); ?>">
                <?php endif; ?>
            </div>
            <div class="mode-detail__summary">
                <h2><?php echo h($name); ?></h2>
                <p><?php echo h((string) ($mode['description'] ?? 'Sem descrição disponível.')); ?></p>
                <ul class="card__meta">
                    <li><b>Duração:</b> <?php echo h((string) ($mode['duration'] ?? 'Não informado')); ?></li>
                    <li><b>Economia:</b> <?php echo h((string) ($mode['economyType'] ?? 'Padrão/não informado')); ?></li>
                    <li><b>Orbs:</b> <?php echo h(isset($mode['orbCount']) ? (string) $mode['orbCount'] : 'N/A'); ?></li>
                    <li><b>Rounds por lado:</b> <?php echo h(isset($mode['roundsPerHalf']) ? (string) $mode['roundsPerHalf'] : 'N/A'); ?></li>
                    <li><b>UUID:</b> <?php echo h((string) ($mode['uuid'] ?? 'Não informado')); ?></li>
                </ul>
            </div>
        </div>

        <div class="mode-detail__grid">
            <article class="card">
                <div class="card__body">
                    <h3 class="card__title">Configurações de partida</h3>
                    <ul class="card__meta">
                        <li><b>Voice de equipe:</b> <?php echo h((isset($mode['isTeamVoiceAllowed']) && $mode['isTeamVoiceAllowed']) ? 'Permitido' : 'Bloqueado'); ?></li>
                        <li><b>Minimapa oculto:</b> <?php echo h((isset($mode['isMinimapHidden']) && $mode['isMinimapHidden']) ? 'Sim' : 'Não'); ?></li>
                        <li><b>Timeout de partida:</b> <?php echo h((isset($mode['allowsMatchTimeouts']) && $mode['allowsMatchTimeouts']) ? 'Permitido' : 'Não permitido'); ?></li>
                        <li><b>Replay customizado:</b> <?php echo h((isset($mode['allowsCustomGameReplays']) && $mode['allowsCustomGameReplays']) ? 'Permitido' : 'Não permitido'); ?></li>
                        <li><b>Funções de equipe:</b> <?php echo h((isset($mode['teamRoles']) && is_array($mode['teamRoles'])) ? (string) count($mode['teamRoles']) : 'Não informado'); ?></li>
                    </ul>
                </div>
            </article>

            <article class="card">
                <div class="card__body">
                    <h3 class="card__title">Overrides de recurso</h3>
                    <?php if (empty($features)): ?>
                        <p class="card__text">Nenhum override de feature foi retornado para este modo.</p>
                    <?php else: ?>
                        <ul class="mode-overrides">
                            <?php foreach ($features as $feature): ?>
                                <li>
                                    <b><?php echo h((string) ($feature['featureName'] ?? 'Feature')); ?></b>
                                    <span><?php echo h((isset($feature['state']) && $feature['state']) ? 'Ativo' : 'Inativo'); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </article>

            <article class="card mode-detail__rules-card">
                <div class="card__body">
                    <h3 class="card__title">Overrides de regras booleanas</h3>
                    <?php if (empty($rules)): ?>
                        <p class="card__text">Nenhuma regra booleana customizada para este modo.</p>
                    <?php else: ?>
                        <div class="table-wrap">
                            <table class="damage-table">
                                <thead>
                                    <tr>
                                        <th>Regra</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rules as $rule): ?>
                                        <tr>
                                            <td><?php echo h((string) ($rule['ruleName'] ?? 'N/A')); ?></td>
                                            <td><?php echo h((isset($rule['state']) && $rule['state']) ? 'Ativo' : 'Inativo'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        </div>

        <div class="mode-detail__actions">
            <a class="btn btn--ghost" href="collections.php?type=gamemodes">Voltar para Modos</a>
            <a class="btn btn--primary" href="collections.php">Ir para outras coleções</a>
        </div>
    </section>
<?php endif; ?>

<?php render_footer(); ?>
