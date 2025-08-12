<?php
session_start();
header("Content-Type: application/json");
include_once("../cls_conectar/cls_conexion.php");

// $inputJSON = file_get_contents("php://input");

// $datos = json_decode($inputJSON, true);

$obj = new Conexion();
$conexion = $obj->getConectar();

$idCliente = (int)$_SESSION["id_cliente"];

//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = "call cerrar_carrito_generar_venta(?);";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idCliente);
$stmt->execute();

$stmt->close();
$conexion->close();

//Devuelve los productos en formato json
// $_SESSION["mensaje"] = "Se realizó la compra";
echo json_encode([
    "estado" => "ok",
    "respuesta" => "Se realizó la compra"
]);