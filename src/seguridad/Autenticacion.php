<?php

// Clase para seguridad de las apis
class Auth
{

    public static function revisarVariableDefinida() {
        if (!defined("MENU_PERMITIDO")) {
            http_response_code(403);
            echo json_encode([
                "estado" => "error",
                "mensaje" => "Acceso directo no permitido a."
            ]);
            exit();
        }
    }

    // Verifica que se haya iniciado session
    public static function revisarLogin()
    {
        
        if (!isset($_SESSION["id_usuario"]) && !isset($_SESSION["id_cliente"])) {

            http_response_code(401);
            echo json_encode(["error" => "Acceso denegado. Por favor, inicie sesión."]);
            exit();
        }
    }

    // Verifica si el rol del usuario tiene permiso para acceder a ciertas apis
    public static function revisarRole($rol_requerido = "")
    {

        self::revisarLogin(); // Primero revisa si está autenticado

        if (!isset($_SESSION["rol_usuario"]) || $_SESSION["rol_usuario"] !== $rol_requerido) {

            http_response_code(403);
            echo json_encode([
                "estado" => "error",
                "mensaje" => "Acceso directo no permitido c."
            ]);
            exit();
        }
    }
}
