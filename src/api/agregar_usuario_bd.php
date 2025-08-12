<?php
session_start();

include_once("../cls_conectar/cls_conexion.php");
$obj = new Conexion();
$conexion = $obj->getConectar();



$usuario = $_POST["txt-usuario"];
$contraseña = $_POST["txt-contraseña"];
$idRol = (int)$_POST["cbo-rol"];


//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = "insert into cuenta (usuario, contrasenia, id_rol, id_estado_cuenta, fecha_creacion, fecha_actualizacion) values (?,?,?,1,now(),now());";
$stmt = $conexion->prepare($sql);

$stmt->bind_param("ssi", $usuario, $contraseña, $idRol);

$result = $stmt->execute();;

$stmt->close();
$conexion->close();

if ($result) {
    // echo "Se agregó el usuario correctamente";
    $_SESSION["mensaje"] = "Se agregó el usuario";
} else {
    // echo "Ocurrió un error";
    $_SESSION["error"] = "No se pudo agregar el usuario";
}

header("Location: ../sistema/indexSistema.php");
exit();
?>