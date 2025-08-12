<?php
session_start();
header("Content-Type: application/json");
include_once("../cls_conectar/cls_conexion.php");

// 1. Leer el cuerpo de la solicitud (JSON crudo)
$inputJSON = file_get_contents("php://input");

// 2. Convertir JSON a arreglo asociativo de PHP
$datos = json_decode($inputJSON, true);

$obj = new Conexion();
$conexion = $obj->getConectar();

$idUsuario = (int)$datos["idUsuario"];

//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = " delete from cuenta WHERE id_cuenta = (?);";
$stmt = $conexion->prepare($sql);

$stmt->bind_param("i", $idUsuario);

$result = $stmt->execute();

$stmt->close();
$conexion->close();

// Obtiene la respuesta de la insercion 
if ($result) {
    $_SESSION["mensaje"] = "Se eliminó el usuario";
} else {
    $_SESSION["error"] = "No se pudo eliminar el usuario";
}
