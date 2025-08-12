<?php
session_start();
$clientelogueado = isset($_SESSION["id_cliente"]);
$btnMensaje = null;
$href = null;
$productosCarrito = [];
if ($clientelogueado) {
    $btnMensaje = "Cerrar Sesion";
    $href = "sistema/logoutControlador.php";
    $datosCarrito = include "./api/obtener_productos_carrito_bd_php.php";
    $productosCarrito = $datosCarrito["respuesta"];
} else {
    $btnMensaje = "Iniciar Sesion";
    $href = "sistema/login.php";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos-cabecera.css">
    <!-- <link rel="stylesheet" href="css/estilos-index.css"> -->
    <link rel="stylesheet" href="css/estilos-comunes.css">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Principal</title>
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
                    <input type="image" class="buscador-boton" src="img/lupa-buscador.png">
                </div>
                <div class="carrito-compra">
                    <a href="carrito.php">
                        <input type="image" src="img/carrito-principal.png" class="carrito-compra-boton">
                    </a>
                </div>
                <div class="inicio-sesion">
                    <a class="inicio-sesion-boton" href="<?php echo $href ?>">
                        <img src="img/inicio-sesion.png">
                        <span><?php echo $btnMensaje ?></span>
                    </a>
                </div>
            </div>
            <div class="barra-menu espacio-lateral">
                <nav class="secciones">
                    <a href="index.php">Principal</a>
                    <a href="productos.php">Productos</a>
                    <a href="servicios.php">Servicio Tecnico</a>
                    <a href="nosotros.php">Nosotros</a>
                    <a href="contacto.php">Contacto</a>
                </nav>
            </div>
        </div>

        <div class="contenedor-contenido espacio-lateral flex">
            <!-- <div id="carrito-mensaje" class="carrito-principal flex"> -->
            <div id="carrito-mensaje" class="flex <?php echo !$clientelogueado || count($productosCarrito) === 0 ? 'carrito-principal-vacio' : 'carrito-principal-con-productos'; ?>">
                <?php if (!$clientelogueado): ?>
                    <i class="fa-solid fa-cart-shopping fa-10x" style="color:rgb(185, 185, 185);"></i>
                    <span class="car-span-texto">Tu carrito está vacío</span>
                    <button id="car-btn-registrar-cliente" class="car-btn-registrar-cliente">Iniciar Sesión</button>
                    <button id="car-btn-ver-productos" class="car-btn-ver-productos">Ver productos</button>

                <?php elseif ($clientelogueado && count($productosCarrito) === 0): ?>
                    <i class="fa-solid fa-cart-shopping fa-10x" style="color:rgb(185, 185, 185);"></i>
                    <span class="car-span-texto">Tu carrito está vacío</span>
                    <button id="car-btn-ver-productos" class="car-btn-ver-productos">Ver productos</button>

                <?php elseif ($clientelogueado && count($productosCarrito) > 0): ?>
                    <?php foreach ($productosCarrito as $producto): 
                        $imgRuta = str_replace("../", "./", $producto["imagen"]);    
                    ?>
                        <div class="carrito-item">
                            <div class="carrito-item-imagen">
                                <img src="<?php echo $imgRuta ?>" alt="<?php echo htmlspecialchars($producto["descripcion"]) ?>">
                            </div>
                            <div class="carrito-item-detalles">
                                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($producto["descripcion"]) ?></p>
                                <p><strong>Categoría:</strong> <?php echo htmlspecialchars($producto["categoria_producto"]) ?></p>
                                <p><strong>Stock Disponible:</strong> <?php echo htmlspecialchars($producto["stock"]) ?> unidades</p>
                                <p><strong>Precio:</strong> S/ <?php echo htmlspecialchars(number_format($producto["precio"], 2)) ?></p>
                            </div>
                            <div class="carrito-item-cantidad">
                                <button class="btn-disminuir" data-id="<?php echo $producto['id_producto'] ?>"><i class="fa-solid fa-minus" style="color: white"></i></button>
                                <span class="cantidad"><?php echo htmlspecialchars($producto["cantidad_seleccionada"]) ?></span>
                                <button class="btn-aumentar" data-id="<?php echo $producto['id_producto'] ?>"><i class="fa-solid fa-plus" style="color: white"></i></button>
                            </div>
                            <div class="carrito-item-eliminar">
                                <button class="btn-eliminar-producto" data-id="<?php echo $producto['id_producto'] ?>"> <i class="fa-solid fa-trash"></i> Quitar </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="carrito-total">
                        <p>Total: <span id="total-carrito">S/ 0.00</span></p>
                    </div>
                    <div class="carrito-finalizar">
                        <button id="btn-finalizar-compra" class="btn-finalizar-compra">
                            <i class="fa-solid fa-cash-register"></i> Finalizar compra
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="pie-pagina">
            <div class="final">
                <p style="color: white;">Siguenos</p>
                <img src="img/logos/facebook.png">
                <img src="img/logos/linkedin.png">
                <img src="img/logos/YT.png">
                <img src="img/logos/instagram.png">
            </div>
        </div>
    </div>
    
    <script src="https://kit.fontawesome.com/1d7c05e90c.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="./js/carrito.js"></script>
    <?php 
        if (isset($_SESSION["mensaje"])) {
            echo "<script>
                Swal.fire({
                    title: '" . $_SESSION["mensaje"] . "',
                    icon: 'success'
                });
            </script>";
            unset($_SESSION["mensaje"]);
        } elseif(isset($_SESSION["error"])) {
            echo "<script>
                Swal.fire({
                    title: '".$_SESSION["error"]."',
                    icon: 'error',
                    draggable: true
                    });
            </script>";
            unset($_SESSION["error"]);
        }
    ?>
</body>

</html>