<?php
function consumirApiGet($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $respuesta = curl_exec($ch);
    $codigo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($respuesta === false || $codigo >= 400) {
        return [
            "ok" => false,
            "codigo" => $codigo,
            "error" => $error ?: "No fue posible consultar la API",
            "datos" => null
        ];
    }
    
    return [
        "ok" => true,
        "codigo" => $codigo,
        "error" => null,
        "datos" => json_decode($respuesta, true)
    ];
}
?>