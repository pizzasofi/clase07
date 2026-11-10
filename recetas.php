<?php
require_once "funciones.php";
$busqueda = trim($_GET["q"] ?? "chicken");
$url = "https://www.themealdb.com/api/json/v1/1/search.php?s=" . urlencode($busqueda);
$resultado = consumirApiGet($url);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recetas</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header class="hero recetas-hero">
        <h1>🍳 Buscador de Recetas</h1>
    </header>
    <main class="contenedor">
        <a class="volver" href="index.php">← Volver</a>
        
        <form method="GET" class="form-busqueda">
            <input type="text" name="q" value="<?= htmlspecialchars($busqueda) ?>" placeholder="Ej: chicken, pasta, cake">
            <button type="submit">🔍 Buscar</button>
        </form>

        <?php if (!$resultado["ok"]): ?>
            <div class="error">❌ Error consultando la API</div>
        <?php else: ?>
            <?php $recetas = $resultado["datos"]["meals"] ?? []; ?>
            <?php if (count($recetas) === 0): ?>
                <div class="aviso">🔍 No se encontraron recetas con "<strong><?= htmlspecialchars($busqueda) ?></strong>"</div>
            <?php else: ?>
                <section class="grid">
                    <?php foreach ($recetas as $receta): ?>
                        <article class="tarjeta tarjeta-receta">
                            <img src="<?= htmlspecialchars($receta["strMealThumb"]) ?>" alt="Receta">
                            <h3><?= htmlspecialchars($receta["strMeal"]) ?></h3>
                            <p><strong>Categoría:</strong> <?= htmlspecialchars($receta["strCategory"]) ?></p>
                            <p><strong>Origen:</strong> <?= htmlspecialchars($receta["strArea"]) ?></p>
                            <?php 
                            $ingredientes = [];
                            for ($i = 1; $i <= 5; $i++) {
                                $ing = trim($receta["strIngredient$i"] ?? '');
                                if ($ing !== '') $ingredientes[] = $ing;
                            }
                            if (!empty($ingredientes)): ?>
                                <div class="ingredientes">
                                    <strong>🥗 Ingredientes:</strong>
                                    <span><?= implode(', ', array_slice($ingredientes, 0, 4)) ?></span>
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>
</html>