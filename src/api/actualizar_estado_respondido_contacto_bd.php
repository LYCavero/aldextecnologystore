<?php

Auth::revisarRole("SOPORTE");

header("Content-Type: application/json");

// 1. Leer el cuerpo de la solicitud (JSON crudo)
$inputJSON = file_get_contents("php://input");

// 2. Convertir JSON a arreglo asociativo de PHP
$datos = json_decode($inputJSON, true);

// include("../cls_conectar/cls_conexion.php");
$obj = new Conexion();
$conexion = $obj->getConectar();

$idContacto = (int)$datos["idContacto"] ?? "";

//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = "
    UPDATE contacto 
    SET 
        id_estado_contacto = 3
    WHERE id_contacto = ?;
";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idContacto);

$result = $stmt->execute();

$stmt->close();
$conexion->close();

// Obtiene la respuesta de la actualización 
if ($result) {
    //Devuelve los productos en formato json
    echo json_encode([
        "estado" => "ok",
        "respuesta" => "Se actualizó el estado el mensaje de contacto a respondido"
    ]);
} else {
    echo json_encode([
        "estado" => "error",
        "respuesta" => "No se pudo actualizar el mensaje de contacto a respondido"
    ]);
}
