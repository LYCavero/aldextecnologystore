<?php
session_start();
header("Content-Type: application/json");
include_once("../cls_conectar/cls_conexion.php");

$inputJSON = file_get_contents("php://input");

$datos = json_decode($inputJSON, true);

$obj = new Conexion();
$conexion = $obj->getConectar();

$idCliente = (int)$_SESSION["id_cliente"];
$idProducto = (int) $datos["idProducto"];
$precioUnitario = (double)$datos["precioUnitario"];

//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = "call agregar_producto_al_carrito(?, ?, 1, ?);";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("iid", $idCliente,$idProducto,$precioUnitario);
$stmt->execute();

$stmt->close();
$conexion->close();

//Devuelve los productos en formato json
echo json_encode([
    "estado" => "ok",
    "respuesta" => "Se agregó el producto al carrito"
]);