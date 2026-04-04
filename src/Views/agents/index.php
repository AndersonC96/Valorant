<?php

declare(strict_types=1);

/** @var array<int, array<string, mixed>> $agents */
?>
<section class="section">
    <p class="hero__eyebrow">Dados de Agentes</p>
    <h1>Agentes</h1>
    <p>Lista inicial de agentes carregada pela arquitetura MVC.</p>

    <?php if (empty($agents)): ?>
        <div class="status status--empty">Nenhum agente disponível no momento.</div>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($agents as $agent): ?>
                <article class="card">
                    <?php if (!empty($agent['displayIcon'])): ?>
                        <div class="card__media">
                            <img src="<?php echo htmlspecialchars((string) $agent['displayIcon'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) ($agent['displayName'] ?? 'Agente'), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    <?php endif; ?>
                    <div class="card__body">
                        <h2 class="card__title"><?php echo htmlspecialchars((string) ($agent['displayName'] ?? 'Sem nome'), ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p class="card__text"><?php echo htmlspecialchars((string) ($agent['description'] ?? 'Sem descrição.'), ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
