<?php
include_once(__DIR__ . "/../cls_conectar/cls_conexion.php");
$obj = new Conexion();
$conexion = $obj->getConectar();

$productosCarrito = [];
$idCliente = $_SESSION["id_cliente"] ?? "";

if($idCliente==""){
    exit;
}

(int)$idCliente;

//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = "
    select 
        ca_d.id_carrito_detalle,
        pro.id_producto,
        pro.descripcion,
        cat_pro.nombre as categoria_producto,
        pro.precio,
        ca_d.cantidad as cantidad_seleccionada,
        pro.stock,
        pro.imagen
    from carrito ca
    left join carrito_detalle ca_d on ca_d.id_carrito = ca.id_carrito
    left join producto pro on pro.id_producto = ca_d.id_producto
    left join categoria_producto cat_pro on cat_pro.id_categoria_producto = pro.id_categoria_producto 
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

$productosCarrito = array_filter($productosCarrito, function($producto){
    return $producto["id_producto"] !== null;
});

$stmt->close();
$conexion->close();

return[
    "respuesta" => $productosCarrito
];
// if($result){

//     //Devuelve los productosCarrito en formato json
//     // echo json_encode([
//     //     "estado" => "ok",
//     //     "respuesta" => $productosCarrito
//     // ]);
// } else {
//     echo json_encode([
//         "estado" => "error",
//         "respuesta" => "No se pudieron obtener los productos del cliente"
//     ]);

// }