<?php
// include("../seguridad/Autenticacion.php");
// Auth::revisarRole("ADMINISTRADOR");
// Auth::revisarLogin();
// session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("../cls_conectar/cls_conexion.php");
$obj = new Conexion();
$conexion = $obj->getConectar();

// header("Content-Type: application/json");

$id_rol = $_SESSION["id_rol"];

$menus = [];

//Consulta para obtener los modulos dependiendo del rol de la cuenta
$sql = "
        select
            m.*,
            case
				when r.id_mod_principal = r_m.id_mod then 1
                else 0
			end as id_mod_principal
        from modulos m
        left join roles_modulos r_m on r_m.id_mod = m.id_modulo
        left join rol r on r.id_rol = r_m.id_rol
        where r_m.id_rol = ?;
        ";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_rol);
$stmt->execute();

$result = $stmt->get_result();

// Mapea todos los menus que el usuario puede visualizar
while ($row = $result->fetch_assoc()) {
    $menus[] = $row;
}

$stmt->close();
$conexion->close();

if ($result) {
    return [
        "estado" => "ok",
        "respuesta" => [
            "menus" => $menus,
            "rolUsuario" => $_SESSION["rol_usuario"]
        ]
    ];
} else {
    return [
        "estado" => "error",
        "respuesta" => "No se pudo obtener los modulos del usuario"
    ];
}

// echo json_encode([
//     "menus" => $menus,
//     "rolUsuario" => $_SESSION["rol_usuario"]
// ]);
