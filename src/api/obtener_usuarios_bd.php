<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// include("../seguridad/Autenticacion.php");
// Auth::revisarRole("ADMINISTRADOR");
// Auth::revisarVariableDefinida();

include_once("../cls_conectar/cls_conexion.php");
$obj = new Conexion();
$conexion = $obj->getConectar();

// header("Content-Type: application/json");

$usuarios = [];
$idUsuario = (int)$_SESSION["id_usuario"] ?? "";

//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = "select
            c.id_cuenta,
            c.usuario,
            c.id_rol,
            r.nombre as rol_cuenta,
            c.contrasenia,
            c.id_estado_cuenta,
            est_c.nombre as nombre_est_cuenta
        from cuenta c
        left join rol r on r.id_rol = c.id_rol
        left join estado_cuenta est_c on est_c.id_estado_cuenta = c.id_estado_cuenta
        where c.id_cuenta != ?;";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i",$idUsuario);
$stmt->execute();

$result = $stmt->get_result();

// Mapea todos los usuarios 
while ($row = $result->fetch_row()) {
    $usuarios[] = $row;
}

$stmt->close();
$conexion->close();

//Devuelve los usuarios en formato json
return [
    "respuesta" => $usuarios
];
// echo json_encode([
//     "usuarios" => $usuarios
// ]);


