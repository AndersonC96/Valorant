<?php

declare(strict_types=1);

/** @var array<int, array<string, mixed>> $agents */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agentes</title>
    <style>
        body { font-family: Arial, sans-serif; background: #0f172a; color: #e2e8f0; margin: 0; }
        main { width: min(1200px, 92vw); margin: 2rem auto; }
        .grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 12px; overflow: hidden; }
        .card img { width: 100%; height: 220px; object-fit: cover; background: #0b1220; }
        .card .content { padding: 1rem; }
        h1 { margin: 0 0 1rem; }
        p { margin: 0.4rem 0 0; color: #cbd5e1; }
    </style>
</head>
<body>
<main>
    <h1>Agentes</h1>
    <div class="grid">
        <?php foreach ($agents as $agent): ?>
            <article class="card">
                <?php if (!empty($agent['displayIcon'])): ?>
                    <img src="<?php echo htmlspecialchars((string) $agent['displayIcon'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) ($agent['displayName'] ?? 'Agente'), ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                <div class="content">
                    <h2><?php echo htmlspecialchars((string) ($agent['displayName'] ?? 'Sem nome'), ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p><?php echo htmlspecialchars((string) ($agent['description'] ?? 'Sem descrição.'), ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>
