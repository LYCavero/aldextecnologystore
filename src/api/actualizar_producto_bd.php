<?php
session_start();
include_once("../cls_conectar/cls_conexion.php");
$obj = new Conexion();
$conexion = $obj->getConectar();

$idProducto = $_POST["txt-idProducto"] ?? "";
$descripcion = $_POST["txt-descripcion"] ?? "";
$precio = (float)$_POST["num-precio"] ?? "";
$stock = (int)$_POST["num-stock"] ?? "";
$idCategoria = (int)$_POST["cbo-categoria-producto"];
$imagenActual = $_POST["hid-imagen"] ?? "";

//Verificar si el archivo se subió
$rutaEnBd = null;

if (isset($_FILES["fil-imagen"]) && $_FILES["fil-imagen"]["error"] === UPLOAD_ERR_OK) {

    $nombreTmp = $_FILES['fil-imagen']['tmp_name'];
    $nombreOriginal = $_FILES['fil-imagen']['name'];
    $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

    if ($extension === "jpg" or $extension === "jpeg" or $extension === "png") {

        $carpetaImgSubidas = "../uploads/";
        $horaSubida = date("YmdHis");
        $nombreImgSubir = "img_" . $horaSubida . "." . $extension;
        $rutaFinal = $carpetaImgSubidas . $nombreImgSubir;

        if (move_uploaded_file($nombreTmp, $rutaFinal)) {

            // Eliminar la imagen antigua si existe y es un archivo válido
            if ($imagenActual && file_exists($imagenActual)) {
                unlink($imagenActual);
            }
            $rutaEnBd = $rutaFinal;
        } else {
            $_SESSION["error"] = "No se logró mover la imagen al directorio";
        }
    } else {
        $_SESSION["error"] = "El tipo de archivo seleccionado no está permitido";
    }
} else {
    $rutaEnBd = $imagenActual;
}

if(isset($_SESSION["error"])){
    header("Location: ../sistema/indexSistema.php");
    exit();
}

//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = "
    UPDATE producto 
    SET 
        descripcion = ?, 
        precio = ?, 
        id_categoria_producto = ?,
        stock = ?, 
        imagen = ? 
    WHERE id_producto = ?;
";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sdiisi", $descripcion, $precio, $idCategoria, $stock, $rutaEnBd,$idProducto);

$result = $stmt->execute();

$stmt->close();
$conexion->close();

if ($result) {
    // echo "Se actualizó el producto correctamente";
    $_SESSION["mensaje"] = "Se actualizó el producto";
} else {
    $_SESSION["error"] = "No se pudo actualizar el producto";
}

header("Location: ../sistema/indexSistema.php");
exit();
