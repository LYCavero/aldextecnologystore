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

//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = "call eliminar_producto_del_carrito(?, ?);";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $idCliente,$idProducto);
$stmt->execute();

$stmt->close();
$conexion->close();

//Devuelve los productos en formato json
echo json_encode([
    "estado" => "ok",
    "respuesta" => "Se eliminó el producto del carrito"
]);