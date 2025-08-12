<?php
session_start();
include("../cls_conectar/cls_conexion.php");
$obj = new Conexion();
$conexion = $obj->getConectar();

//leer los datos del formulario login.php
$nom = $_POST["usuario"];
$con = $_POST["contraseña"];

//query
$sql = "
    select 
        c.id_cuenta,
        c.usuario,
        r.id_rol,
        r.nombre
    from cuenta c
    left join rol r on r.id_rol = c.id_rol
    where c.usuario = ? and c.contrasenia = ?;";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $nom, $con);
$stmt->execute();
$resultado = $stmt->get_result();

//Primero busca si existe una cuenta administrador
// if ($resultado->num_rows === 1) {
//     $res = $resultado->fetch_array();
//     $_SESSION["id_usuario"] = $res[0];
//     $_SESSION["usuario"] = $res[1];
//     $_SESSION["id_rol"] = $res[2];
//     $_SESSION["rol_usuario"] = $res[3];
//     header("location:indexSistema.php");
//     exit;
// } else {
//     $_SESSION["error"] = "Usuario o contraseña incorrectos";
//     header("location:login.php");
//     exit();
// }

//Primero busca si existe una cuenta administrador
if($resultado->num_rows===1){
    $res = $resultado -> fetch_array();
    $_SESSION["id_usuario"] = $res[0];
    $_SESSION["usuario"] = $res[1];
    $_SESSION["id_rol"] = $res[2];
    $_SESSION["rol_usuario"] = $res[3];
    header("location:indexSistema.php");
    exit;
} else {
    // Buscar en la tabla cliente
    $sqlCliente = "
        SELECT 
            id_cliente, 
            email
        FROM cliente 
        WHERE email=? AND contrasenia=?;";

    $stmt=$conexion->prepare($sqlCliente);
    $stmt->bind_param("ss",$nom,$con);
    $stmt->execute();
    $resultadoCliente = $stmt->get_result();

    if($resultadoCliente->num_rows===1){
        $resCliente = $resultadoCliente->fetch_array();
        $_SESSION["id_cliente"] = $resCliente[0];
        $_SESSION["correo_cliente"] = $resCliente[1];
        // Redirigir al home de la tienda o quedarse en el ecommerce:
        header("location:../index.php");
        exit;
    } else {
        $_SESSION["error"] = "Usuario o contraseña incorrectos";
        header("location:login.php");
        exit();
    }
}

$stmt->close();
$conexion->close();
