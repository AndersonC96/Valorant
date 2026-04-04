<?php

declare(strict_types=1);
?>
<section class="section">
    <p class="hero__eyebrow">Erro 404</p>
    <h1>Página não encontrada</h1>
    <p>A rota solicitada não existe nesta versão da aplicação.</p>
    <p><strong>URL:</strong> <?php echo htmlspecialchars((string) ($path ?? '/'), ENT_QUOTES, 'UTF-8'); ?></p>
    <div class="hero__actions">
        <a class="btn btn--primary" href="agentes">Ir para Agentes</a>
        <a class="btn btn--ghost" href="colecoes">Ir para Coleções</a>
    </div>
</section>
