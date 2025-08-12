<?php
//Este controlador obtendrá los bloques HTML de los menus en un json
session_start();
header("Content-Type: application/json");
include("../seguridad/Autenticacion.php");

include("../cls_conectar/cls_conexion.php");
$obj = new Conexion();
$conexion1 = $obj->getConectar();

// Se obtiene el menú que el usuario quiere visualizar
$menu= $_GET["menu"] ?? '';

$sql = "
    select
        m.*
    from modulos m
    left join roles_modulos r_m on r_m.id_mod = m.id_modulo
    where r_m.id_rol = (?) and m.slug = (?);
";

$stmt = $conexion1 -> prepare($sql);
$stmt -> bind_param("is",$_SESSION["id_rol"],$menu);
$stmt -> execute();

$result = $stmt ->get_result();

if($result->num_rows === 0){

    http_response_code(403);
    echo json_encode([
        "estado"=>"error",
        "codigo"=>"403",
        "respuesta"=>"No tienes permiso para acceder a este modulo"
    ]);
    exit();
}

$ruta_archivo_contenido="../fragmentos-contenido/" . $menu . "-contenido.php"; 

if (!file_exists($ruta_archivo_contenido)){

    http_response_code(404);
    echo json_encode([
        "estado"=>"warning",
        "respuesta"=>"El archivo del contenido no fue encontrado"
    ]);

} else {
    
    define("MENU_PERMITIDO",true);
    ob_start();
    //Obtiene el bloque html del contenido del menú, con include
    include $ruta_archivo_contenido;
    $html = ob_get_clean();

    echo json_encode([
        "estado"=>"ok",
        "respuesta"=> $html
    ]);

}


?>