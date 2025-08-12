<?php
    class Conexion{
        function getConectar(){
            //variakbes 
            $server="cont-db-mysql-dev:3306";
            $login="root";
            $clave="root";
            $bd="bd_aldex_tecnology_store";
            //function
            $cn=mysqli_connect($server,$login,$clave,$bd);
            return $cn;
        }
    }
?>