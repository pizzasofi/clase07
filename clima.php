<?php require_once "funciones.php";
$ciudad = trim($_GET["ciudad"] ?? "Bogota");
$clima = null;
$mensaje = null;

if ($ciudad !== "") {
    $geoUrl = "https://geocoding-api.open-meteo.com/v1/search?name=" . urlencode($ciudad) . "&count=1&language=es&format=json";
    $geo = consumirApiGet($geoUrl);
    
    if ($geo["ok"] && !empty($geo["datos"]["results"])) {
        $lugar = $geo["datos"]["results"][0];
        $lat = $lugar["latitude"];
        $lon = $lugar["longitude"];
        
        $urlClima = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&current=temperature_2m,relative_humidity_2m,wind_speed_10m&timezone=auto";
        $clima = consumirApiGet($urlClima);
    } else {
        $mensaje = "Ciudad no encontrada. Intenta con otra.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clima</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header class="hero clima-hero">
        <h1>🌤️ Clima por Ciudad</h1>
    </header>
    <main class="contenedor">
        <a class="volver" href="index.php">← Volver</a>
        
        <form method="GET" class="form-clima">
            <input type="text" name="ciudad" value="<?= htmlspecialchars($ciudad) ?>" placeholder="Escribe una ciudad...">
            <button type="submit">📡 Consultar</button>
        </form>

        <?php if ($mensaje): ?>
            <div class="error"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <?php if ($clima && $clima["ok"]): ?>
            <?php $actual = $clima["datos"]["current"]; 
            $temp = $actual["temperature_2m"];
            
            if ($temp < 15) {
                $estado = "🥶 Frío";
                $clase = "frio";
                $sugerencia = "¡Abrígate bien! 🧥";
            } elseif ($temp <= 25) {
                $estado = "🌡️ Templado";
                $clase = "templado";
                $sugerencia = "¡Excelente día! 😊";
            } else {
                $estado = "🔥 Calor";
                $clase = "calor";
                $sugerencia = "¡Hidrátate bien! 💧";
            }
            ?>
            <article class="tarjeta tarjeta-clima <?= $clase ?>">
                <h2>📍 <?= htmlspecialchars($ciudad) ?></h2>
                <div class="temperatura-grande"><?= $temp ?>°C</div>
                <p class="estado-clima"><strong><?= $estado ?></strong></p>
                <p>💧 Humedad: <?= $actual["relative_humidity_2m"] ?>%</p>
                <p>💨 Viento: <?= $actual["wind_speed_10m"] ?> km/h</p>
                <div class="sugerencia"><?= $sugerencia ?></div>
            </article>
        <?php endif; ?>
    </main>
</body>
</html>