<?php
session_start();

include_once("../cls_conectar/cls_conexion.php");
$obj = new Conexion();
$conexion = $obj->getConectar();

$idUsuario = (int)$_POST["txt-idUsuario"];
$usuario = $_POST["txt-usuario"];
$contraseña = $_POST["txt-contraseña"];
$idRol = (int)$_POST["cbo-rol"];
$idEstado = (int)$_POST["cbo-estado"];

//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = "
    update cuenta 
    set 
        usuario = ?, 
        contrasenia = ? ,
        id_rol = ? ,
        id_estado_cuenta = ?
    WHERE id_cuenta = ? ;
";
$stmt = $conexion->prepare($sql);

$stmt->bind_param("ssiii", $usuario, $contraseña, $idRol, $idEstado, $idUsuario);

$result = $stmt->execute();;

$stmt->close();
$conexion->close();

if ($result) {
    // echo "Se actualizó correctamente";
    $_SESSION["mensaje"] = "Se actualizó el usuario";
} else {
    // echo "Ocurrió un error";
    $_SESSION["error"] = "No se pudo actualizar el usuario";
}

header("Location: ../sistema/indexSistema.php");
exit();
?>