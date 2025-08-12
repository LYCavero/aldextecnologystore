<?php
header("Content-Type: application/json");

Auth::revisarVariableDefinida();

// include("../cls_conectar/cls_conexion.php");
$obj = new Conexion();
$conexion = $obj->getConectar();


$productos = [];

//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = "
    select 
        p.id_producto,
        p.descripcion,
        p.precio,
        p.stock,
        p.imagen,
        p.id_categoria_producto,
        ct_p.nombre as categoria
    from producto p
    left join categoria_producto ct_p on ct_p.id_categoria_producto = p.id_categoria_producto
    order by 1 desc;
";

$stmt = $conexion->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();

// Mapea todos los productos 
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

$stmt->close();
$conexion->close();

//Devuelve los productos en formato json
echo json_encode([
    "estado" => "ok",
    "respuesta" => $productos
]);