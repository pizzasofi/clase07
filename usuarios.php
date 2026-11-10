<?php require_once "funciones.php";
$cantidad = isset($_GET["cantidad"]) ? (int)$_GET["cantidad"] : 6;
$cantidad = max(1, min(12, $cantidad));
$resultado = consumirApiGet("https://randomuser.me/api/?results=" . $cantidad);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personas</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header class="hero usuarios-hero">
        <h1>👥 Personas Aleatorias</h1>
    </header>
    <main class="contenedor">
        <a class="volver" href="index.php">← Volver</a>
        
        <form method="GET" class="form-filtro">
            <input type="number" name="cantidad" min="1" max="12" value="<?= $cantidad ?>" placeholder="Cantidad">
            <button type="submit">🔄 Consultar</button>
        </form>

        <?php if (!$resultado["ok"]): ?>
            <div class="error"><?= htmlspecialchars($resultado["error"]) ?></div>
        <?php else: ?>
            <section class="grid">
                <?php foreach ($resultado["datos"]["results"] as $persona): ?>
                    <article class="tarjeta tarjeta-persona">
                        <img src="<?= htmlspecialchars($persona["picture"]["large"]) ?>" alt="Foto de perfil">
                        <h3><?= htmlspecialchars($persona["name"]["first"] . " " . $persona["name"]["last"]) ?></h3>
                        <p class="email">📧 <?= htmlspecialchars($persona["email"]) ?></p>
                        <p>📍 <?= htmlspecialchars($persona["location"]["city"]) ?>, <?= htmlspecialchars($persona["location"]["country"]) ?></p>
                        <span class="badge">Nacionalidad: <?= htmlspecialchars($persona["nat"]) ?></span>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>