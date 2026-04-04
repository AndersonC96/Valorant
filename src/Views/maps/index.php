<?php

declare(strict_types=1);

/** @var array<int, array<string, mixed>> $maps */
?>
<section class="section">
    <p class="hero__eyebrow">Dados de Mapas</p>
    <h1>Mapas</h1>
    <p>Lista inicial de mapas carregada pela arquitetura MVC.</p>

    <?php if (empty($maps)): ?>
        <div class="status status--empty">Nenhum mapa disponível no momento.</div>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($maps as $map): ?>
                <article class="card">
                    <?php if (!empty($map['splash'])): ?>
                        <div class="card__media">
                            <img src="<?php echo htmlspecialchars((string) $map['splash'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) ($map['displayName'] ?? 'Mapa'), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    <?php endif; ?>
                    <div class="card__body">
                        <h2 class="card__title"><?php echo htmlspecialchars((string) ($map['displayName'] ?? 'Sem nome'), ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p class="card__text"><?php echo htmlspecialchars((string) ($map['coordinates'] ?? 'Sem coordenadas.'), ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
