<?php

Auth::revisarLogin();

header("Content-Type: application/json");

$carpeta = __DIR__ . "/../js/vistas/";

if (is_dir($carpeta)) {

    $archivos = array_diff(scandir($carpeta), array(".", ".."));

    echo json_encode([
        "estado" => "ok",
        "respuesta" => array_values($archivos)
    ]);
} else {
    
    echo json_encode([
        "estado" => "error",
        "respuesta" => "La carpeta de los archivos .js de las vistas no existe"
    ]);
}
