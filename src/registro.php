<?php
session_start();
// Si ya está logueado, lo redirige a indexSistema.php
if(isset($_SESSION["id_usuario"])){
  header("Location: indexSistema.php");
  exit();
} 
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro</title>
  <link rel="stylesheet" href="css/estilos-cabecera.css">
  <link rel="stylesheet" href="css/estilos-comunes.css">
  <link rel="stylesheet" href="css/estilos-login.css">
</head>

<body>
  <div class="contenedor-principal">
    <div class="fondo-cabecera">
      <div class="cabecera espacio-lateral">
        <div class="logo">
          <img src="img/logos/logo.webp">
          <div class="categoria">
            <input type="image" src="img/cabecera/menu-barra.png" class="categoria-boton">
            <div class="categoria-contenedor-lista">
              <h3>Especificaciones</h3>
              <ul>
                <li>Procesador</li>
                <li>Memoria RAM</li>
                <li>Almacenamiento</li>
                <li>Gráficos / Tarjeta de video</li>
                <li>Pantalla</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="buscador">
          <input type="text" placeholder="Buscar productos" class="buscador-caja">
          <input type="image" class="buscador-boton" src="img/lupa-buscador.png">
        </div>
        <div class="inicio-sesion">
          <a href="login.php" class="inicio-sesion-boton">
            <img src="img/inicio-sesion.png">
            <span>Inicio Sesión</span>
          </a>
        </div>
        <div class="carrito-compra">
          <input type="image" src="img/carrito-principal.png" class="carrito-compra-boton">
        </div>
      </div>
      <div class="barra-menu espacio-lateral">
        <nav class="secciones">
          <a href="index.php">Principal</a>
          <a href="productos.php">Productos</a>
          <a href="servicios.php">Servicio Técnico</a>
          <a href="nosotros.php">Nosotros</a>
          <a href="contacto.php">Contacto</a>
        </nav>
      </div>
    </div>

    <div class="contenedor-contenido espacio-lateral">
      <div class="contenedor-login">
        <h2>Regístrate</h2>
        <form method="POST" action="registroControlador.php" class="contenedor-formulario">
          <div class="espaciado-vertical">
            <label class="lbl-login en-bloque">Nombre completo</label>
            <input class="input-login" type="text" name="nombre" required>
          </div>
          <div class="espaciado-vertical">
            <label class="login-label en-bloque">Correo electrónico</label>
            <input class="input-login" type="email" name="correo" required>
          </div>
          <div class="espaciado-vertical">
            <label class="login-label en-bloque">Contraseña</label>
            <input class="input-login" type="password" name="contrasena" required>
          </div>
          <div class="espaciado-vertical">
            <label class="login-label en-bloque">Confirmar contraseña</label>
            <input class="input-login" type="password" name="confirmar_contrasena" required>
          </div>
          <button class="login-boton en-bloque" type="submit">Registrarse</button>
        </form>
        <div class="pie-formulario">
          ¿Ya tienes cuenta?
          <a href="sistema/login.php" style="font-weight: 600;">Inicia sesión</a>
        </div>
      </div>
    </div>

    <div class="pie-pagina">
      <div class="final">
        <p style="color: white;">Síguenos</p>
        <img src="img/logos/facebook.png">
        <img src="img/logos/linkedin.png">
        <img src="img/logos/YT.png">
        <img src="img/logos/instagram.png">
      </div>
    </div>
  </div>
</body>

</html>