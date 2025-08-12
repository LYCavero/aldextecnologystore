<?php
header("Content-Type: application/json");
$obj = new Conexion();
$conexion = $obj->getConectar();

$productosCarrito = [];
$idCliente = $_SESSION["id_cliente"] ?? "";

if($idCliente==""){
    echo json_encode([
        "estado" => "ok",
        // "respuesta" => "No existe un cliente logueado"
        "respuesta" => $productosCarrito,
        "logueado" => false
    ]);
    exit;
}

(int)$idCliente;

//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = "
    select 
        ca_d.id_carrito_detalle,
        ca_d.cantidad as cantidad_seleccionada,
        pro.id_producto,
        pro.descripcion,
        pro.precio,
        pro.stock,
        pro.imagen
    from carrito ca
    left join carrito_detalle ca_d on ca_d.id_carrito = ca.id_carrito
    left join producto pro on pro.id_producto = ca_d.id_producto
    where id_cliente = ? AND ca.id_estado_carrito = 1;
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i",$idCliente);
$stmt->execute();

$result = $stmt->get_result();

// Mapea todos los productosCarrito 
while ($row = $result->fetch_assoc()) {
    $productosCarrito[] = $row;
}

$stmt->close();
$conexion->close();

if($result){

    //Devuelve los productosCarrito en formato json
    echo json_encode([
        "estado" => "ok",
        "respuesta" => $productosCarrito,
        "logueado" => true
    ]);

} else {
    echo json_encode([
        "estado" => "error",
        "respuesta" => "No se pudieron obtener los productos del cliente"
    ]);
}