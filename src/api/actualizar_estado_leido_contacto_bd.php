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
$sql = "call marcar_contacto_si_no_leido(?)";
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
        "respuesta" => "Se ejecutó el procedimiento almacenado correctamente"
    ]);
} else {
    echo json_encode([
        "estado" => "error",
        "respuesta" => "No se pudo ejecutar el procedimiento almacenado correctamente"
    ]);
}
