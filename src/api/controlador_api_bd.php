<?php
session_start();
include("../seguridad/Autenticacion.php");
include("../cls_conectar/cls_conexion.php");
header("Content-Type:application/json");

//Se obtiene el archivo que contiene el query a llamar
$archivo_api_bd = $_GET["archivo"] ?? '';

//Agregamos los archivos que contienen querys 
// $archivos_api_bd_disponibles = [
//     "obtener_menus_bd.php",

//     "obtener_usuarios_bd.php",
//     "agregar_usuario_bd.php",
//     "actualizar_usuario_bd.php",
//     "eliminar_usuario_bd.php"
// ];

// //Verificamos si el archivo obtenido existe en el arreglo
// if (!in_array($archivo_api_bd, $archivos_api_bd_disponibles)) {
//     echo json_encode([
//         "error" => "Archivo '". $archivo_api_bd ."' no encontrado."
//     ]);
//     exit();
// }

$ruta_archivo_api_bd = "./" . $archivo_api_bd;

if (file_exists($ruta_archivo_api_bd)) {

    //Obtiene el archivo .php con el query a utilizar
    define("MENU_PERMITIDO", true);
    include $ruta_archivo_api_bd;
} else {
    http_response_code(404);
    echo json_encode([
        "estado" => "warning",
        "respuesta" => "El archivo solicitado no existe."
    ]);

}
