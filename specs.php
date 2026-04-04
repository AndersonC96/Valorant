<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

render_head('Valorant Atlas | Requisitos', 'Requisitos mínimos, recomendados e high-end para rodar Valorant no PC.');
render_header('specs');
?>
<section class="hero">
    <div>
        <p class="hero__eyebrow">Preparação Técnica</p>
        <h1>Requisitos de hardware para uma experiência competitiva estável</h1>
        <p>
            Esta seção reúne os requisitos de máquina de forma objetiva para facilitar o planejamento de desempenho.
            O foco é comparar níveis de configuração com clareza e sem ruído visual.
        </p>
    </div>
    <aside class="hero__panel" aria-label="Imagem de mapa do Valorant">
        <img src="images/mapsintro.png" alt="Cenario de mapa do Valorant">
    </aside>
</section>

<section class="section">
    <h2>Base do sistema</h2>
    <div class="spec-grid">
        <article class="spec-item">
            <h3>Sistema Operacional</h3>
            <p>Windows 10 ou 11 64-bit.</p>
        </article>
        <article class="spec-item">
            <h3>Memória RAM</h3>
            <p>Mínimo de 4 GB para inicialização estável do jogo.</p>
        </article>
        <article class="spec-item">
            <h3>Video (VRAM)</h3>
            <p>1 GB de VRAM como base para o perfil de entrada.</p>
        </article>
        <article class="spec-item">
            <h3>Segurança</h3>
            <p>No Windows 11, TPM 2.0 e Secure Boot habilitados sao obrigatorios.</p>
        </article>
    </div>
</section>

<section class="section">
    <h2>Perfis de desempenho</h2>
    <div class="spec-grid">
        <article class="spec-item">
            <h3>Mínimo - 30 FPS</h3>
            <p><strong>CPU:</strong> Intel Core 2 Duo E8400 ou Athlon 200GE</p>
            <p><strong>GPU:</strong> Intel HD 4000 ou Radeon R5 200</p>
        </article>
        <article class="spec-item">
            <h3>Recomendado - 60 FPS</h3>
            <p><strong>CPU:</strong> Intel i3-4150 ou Ryzen 3 1200</p>
            <p><strong>GPU:</strong> GeForce GT 730 ou Radeon R7 240</p>
        </article>
        <article class="spec-item">
            <h3>High-end - 144+ FPS</h3>
            <p><strong>CPU:</strong> Intel i5-9400F ou Ryzen 5 2600X</p>
            <p><strong>GPU:</strong> GTX 1050 Ti ou Radeon R7 370</p>
        </article>
    </div>
</section>
<?php render_footer(); ?>
