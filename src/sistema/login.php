<?php
session_start();

// Si ya se encuentra logueado, lo reedirige al indexSistema.php
if (isset($_SESSION["id_usuario"])) {
  header("Location: indexSistema.php");
  exit();
} elseif (isset($_SESSION["id_cliente"])) {
  header("Location: ../index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/estilos-cabecera.css">
  <link rel="stylesheet" href="../css/estilos-comunes.css">
  <link rel="stylesheet" href="../css/estilos-login.css">
  <title>Login</title>
  <script src="../js/operaciones.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="contenedor-principal">
    <div class="fondo-cabecera">
      <div class="cabecera espacio-lateral">
        <div class="logo">
          <img src="../img/logos/logo.webp">
          <div class="categoria">
            <input type="image" src="../img/cabecera/menu-barra.png" class="categoria-boton">
            <div class="categoria-contenedor-lista">
              <h3>Especificaciones</h3>
              <ul>
                <li>Procesador
                <li>Memoria RAM
                <li>Almacenamiento
                <li>Gráficos / Tarjeta de video
                <li>Pantalla
              </ul>
            </div>
          </div>
        </div>
        <div class="buscador">
          <input type="text" placeholder="Buscar productos" class="buscador-caja">
          <input type="image" class="buscador-boton" src="../img/lupa-buscador.png">
        </div>
        <div class="inicio-sesion">
          <a href="login.php" class="inicio-sesion-boton">
            <img src="../img/inicio-sesion.png">
            <span>Inicio Sesion</span>
          </a>
        </div>
        <div class="carrito-compra">
          <a href="../carrito.php">
            <input type="image" src="../img/carrito-principal.png" class="carrito-compra-boton">
          </a>
        </div>
      </div>
      <div class="barra-menu espacio-lateral">
        <nav class="secciones">
          <a href="../index.php">Principal</a>
          <a href="../productos.php">Productos</a>
          <a href="../servicios.php">Servicio Tecnico</a>
          <a href="../nosotros.php">Nosotros</a>
          <a href="../contacto.php">Contacto</a>
        </nav>
      </div>
    </div>
    <div class="contenedor-contenido espacio-lateral">
      <div class="contenedor-login">
        <h2>Iniciar Sesión</h2>
        <?php

        if (isset($_SESSION["error"])) {
          echo "<script>mensajeNoLogueado();</script>";
          unset($_SESSION["error"]);
        }
        ?>
        <form method="POST" action="loginControlador.php" class="contenedor-formulario">
          <div class="espaciado-vertical">
            <label class="login-label en-bloque">Usuario</label>
            <input class="input-login" type="text" id="usuario" name="usuario" required>
          </div>
          <div class="espaciado-vertical">
            <label class="login-label en-bloque">Contraseña</label>
            <input class="input-login" type="password" id="contraseña" name="contraseña" required>
          </div>
          <button class="login-boton en-bloque" type="submit">Ingresar</button>
        </form>
        <div class="pie-formulario">
          ¿No tienes cuenta?
          <a href="../registro.php" style="font-weight: 600;"> Regístrate </a>
        </div>
      </div>
    </div>
    <div class="pie-pagina">
      <div class="final">
        <p style="color: white;">Siguenos</p>
        <img src="../img/logos/facebook.png">
        <img src="../img/logos/linkedin.png">
        <img src="../img/logos/YT.png">
        <img src="../img/logos/instagram.png">
      </div>
    </div>
  </div>
</body>

</html>