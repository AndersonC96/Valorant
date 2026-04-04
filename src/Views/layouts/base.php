<?php

declare(strict_types=1);

$docTitle = $title ?? 'Valorant Atlas';
$active = $active ?? '';

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
if ($basePath === '') {
    $basePath = '/';
}

$toUrl = static function (string $path) use ($basePath): string {
    if ($basePath === '/') {
        return $path;
    }
    return $basePath . $path;
};
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars((string) $docTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($toUrl('/images/favicon.png'), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($toUrl('/css/normalize.css'), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($toUrl('/css/app.css'), ENT_QUOTES, 'UTF-8'); ?>">
</head>
<body>
<header class="site-header">
    <div class="site-header__inner">
        <a class="brand" href="<?php echo htmlspecialchars($toUrl('/'), ENT_QUOTES, 'UTF-8'); ?>" aria-label="Valorant Atlas - início">
            <span class="brand__mark">VA</span>
            <span class="brand__text">Valorant Atlas</span>
        </a>
        <button class="nav-toggle" aria-expanded="false" aria-controls="main-nav">Menu</button>
        <nav id="main-nav" class="main-nav" aria-label="Navegação principal">
            <a class="<?php echo $active === 'agentes' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars($toUrl('/agentes'), ENT_QUOTES, 'UTF-8'); ?>">Agentes</a>
            <a class="<?php echo $active === 'mapas' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars($toUrl('/mapas'), ENT_QUOTES, 'UTF-8'); ?>">Mapas</a>
            <a class="<?php echo $active === 'colecoes' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars($toUrl('/colecoes'), ENT_QUOTES, 'UTF-8'); ?>">Coleções</a>
        </nav>
    </div>
</header>
<main>
    <?php echo $content; ?>
</main>
<footer class="site-footer">
    <div class="site-footer__inner">
        <p>Aplicação independente de consulta de dados, alimentada pela API pública Valorant-API.</p>
        <p>&copy; <?php echo date('Y'); ?> Valorant Atlas.</p>
    </div>
</footer>
<script src="<?php echo htmlspecialchars($toUrl('/js/app.js'), ENT_QUOTES, 'UTF-8'); ?>" defer></script>
</body>
</html>
