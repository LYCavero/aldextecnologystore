<?php

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

    class Conexion{
        function getConectar(){
            //variakbes 
            $server=getenv("DB_HOST");
            $login=getenv("DB_USER");
            $clave=getenv("DB_PASS");
            $bd=getenv("DB_NAME");
            //function
            $cn=mysqli_connect($server,$login,$clave,$bd);
            return $cn;
        }
    }
?>