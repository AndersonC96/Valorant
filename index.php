<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$highlights = [];
$errors = [];

foreach (['agents', 'weapons', 'maps'] as $type) {
    $collection = app_collection($type);
    if ($collection === null) {
        continue;
    }

    $result = api_fetch($collection['endpoint']);
    if (!$result['ok']) {
        $errors[] = $collection['label'] . ': ' . $result['error'];
        continue;
    }

    $highlights[] = [
        'label' => $collection['label'],
        'count' => count($result['data']),
        'description' => $collection['description'],
        'link' => 'collections.php?type=' . $type,
    ];
}

render_head('Valorant Atlas | Inicio', 'Aplicacao de portfolio para explorar dados oficiais do universo Valorant.');
render_header('home');
?>
<section class="hero">
    <div>
        <p class="hero__eyebrow">Portfolio Frontend + PHP</p>
        <h1>Valorant Atlas: explorador moderno de dados do universo Valorant</h1>
        <p>
            Esta aplicacao reorganiza o consumo da Valorant-API em uma experiencia unica, com foco nas entidades mais relevantes do jogo,
            navegacao consistente e tratamento de estados para erros, ausencia de dados e respostas incompletas.
        </p>
        <div class="hero__actions">
            <a class="btn btn--primary" href="collections.php?type=agents">Explorar agentes</a>
            <a class="btn btn--ghost" href="collections.php">Ver todas as colecoes</a>
            <a class="btn btn--ghost" href="specs.php">Requisitos para jogar</a>
        </div>
    </div>
    <aside class="hero__panel" aria-label="Imagem de destaque do Valorant">
        <img src="images/omen_II.png" alt="Agente Omen em arte oficial do Valorant">
    </aside>
</section>

<section class="section">
    <h2>Panorama em tempo real</h2>
    <p>Resumo das principais colecoes retornadas pela API publica. Se houver indisponibilidade, o feedback aparece abaixo.</p>

    <?php if (!empty($errors)): ?>
        <div class="status status--error">
            <strong>Algumas fontes nao responderam.</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo h($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="metrics">
        <?php foreach ($highlights as $item): ?>
            <article class="metric">
                <strong><?php echo h((string) $item['count']); ?></strong>
                <span><?php echo h($item['label']); ?></span>
                <p><?php echo h($item['description']); ?></p>
                <a href="<?php echo h($item['link']); ?>">Abrir colecao</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <h2>Foco curatorial do projeto</h2>
    <div class="grid">
        <article class="card">
            <div class="card__body">
                <h3 class="card__title">Agentes</h3>
                <p class="card__text">Funcao tatica, identidades e contexto de gameplay para estudo de design de personagens.</p>
            </div>
        </article>
        <article class="card">
            <div class="card__body">
                <h3 class="card__title">Armas</h3>
                <p class="card__text">Leitura de economia e classes de armamento com dados de custo e categoria.</p>
            </div>
        </article>
        <article class="card">
            <div class="card__body">
                <h3 class="card__title">Mapas</h3>
                <p class="card__text">Visao espacial com callouts e coordenadas para orientar estrategias e navegacao.</p>
            </div>
        </article>
    </div>
</section>
<?php render_footer(); ?>