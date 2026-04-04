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

render_head('Valorant Atlas | Início', 'Aplicação para explorar dados oficiais do universo Valorant.');
render_header('home');
?>
<section class="hero">
    <div>
        <p class="hero__eyebrow">Aplicação Frontend + PHP</p>
        <h1>Valorant Atlas: explorador moderno de dados do universo Valorant</h1>
        <p>
            O Valorant Atlas reúne os dados da Valorant-API em uma experiência coesa, com foco nas entidades mais relevantes do jogo,
            navegação consistente e tratamento claro para erros, ausência de dados e respostas incompletas.
        </p>
        <div class="hero__actions">
            <a class="btn btn--primary" href="collections.php?type=agents">Explorar agentes</a>
            <a class="btn btn--ghost" href="collections.php">Ver todas as coleções</a>
            <a class="btn btn--ghost" href="specs.php">Requisitos para jogar</a>
        </div>
    </div>
    <aside class="hero__panel" aria-label="Imagem de destaque do Valorant">
        <img src="images/omen_II.png" alt="Agente Omen em arte oficial do Valorant">
    </aside>
</section>

<section class="section">
    <h2>Panorama em tempo real</h2>
    <p>Resumo das principais coleções retornadas pela API pública. Se houver indisponibilidade, o aviso aparece abaixo.</p>

    <?php if (!empty($errors)): ?>
        <div class="status status--error">
            <strong>Algumas fontes não responderam.</strong>
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
                <a href="<?php echo h($item['link']); ?>">Abrir coleção</a>
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
                <p class="card__text">Função tática, identidades e contexto de gameplay para estudo de design de personagens.</p>
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
                <p class="card__text">Visão espacial com callouts e coordenadas para orientar estratégias e navegação.</p>
            </div>
        </article>
    </div>
</section>
<?php render_footer(); ?>