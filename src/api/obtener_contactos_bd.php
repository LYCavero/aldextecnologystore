<?php

// include("../seguridad/Autenticacion.php");
// Auth::revisarRole("ADMINISTRADOR");
// Auth::revisarVariableDefinida();

include_once("../cls_conectar/cls_conexion.php");
$obj = new Conexion();
$conexion = $obj->getConectar();

// header("Content-Type: application/json");

$contactos = [];

//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = "
    select
        c.id_contacto,
        c.nombre as cliente,
        c.mensaje,
        c.correo_electronico,
        c.id_estado_contacto,
        est_c.nombre as estado_contacto
    from contacto c
    left join estado_contacto est_c on est_c.id_estado_contacto = c.id_estado_contacto;
";

$stmt = $conexion->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();

// Mapea todos los contactos 
while ($row = $result->fetch_assoc()) {
    $contactos[] = $row;
}

$stmt->close();
$conexion->close();

//Devuelve los contactos en formato json
// echo json_encode([
//     "estado" => "ok",
//     "respuesta" => $contactos
// ]);

return [
    "respuesta" => $contactos
];