<?php
session_start();

$clientelogueado = isset($_SESSION["id_cliente"]);
$btnMensaje = null;
if ($clientelogueado) {
    $btnMensaje = "Cerrar Sesion";
    $href = "sistema/logoutControlador.php";
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
    <title>Contacto</title>
    <link rel="stylesheet" href="css/estilos-contacto.css">
    <link rel="stylesheet" href="css/estilos-cabecera.css">
    <link rel="stylesheet" href="css/estilos-comunes.css">
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
        <main class="contenedor-contenido">
            <section class="info-contacto">
                <h3>CONTACTANOS</h3>
                <p>📞 (+51) 4206212</p>
                <p>📧 <a href="mailto:Aldex.store@.com">Aldex.store@.com</a></p>
                <p>📍 Los Olivos - calle C</p>

                <h4>POLÍTICAS</h4>
                <p>Politicas de privacidad</p>

                <h4>SERVICIOS</h4>
                <ul>
                    <li>Soprte Técnico</li>
                    <li>Armado de PC</li>
                    <li>Mantenimiento</li>
                </ul>

                <h4>NOSOTROS</h4>
                <ul>
                    <li>🚚 Envíos a todo el país</li>
                    <li>🧑‍💼 Atención personalizada</li>
                    <li>📄 Canal de denuncias</li>
                </ul>
                <div class="Mapa">
                    <h4>Mapa del Sitio</h4>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1640.5221795543775!2d-77.03928005201729!3d-12.054438320157058!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c9458f370c19%3A0xde5aa623bea14793!2sServicio%20T%C3%A9cnico%20Wilson%20S.A.C.!5e0!3m2!1ses-419!2spe!4v1745299719499!5m2!1ses-419!2spe"
                        width="300" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

            </section>
            <section class="formulario-contacto">
                <h3>Escribenos desde aquí : </h3>
                <form>
                    <label for="nombre">Tu nombre: </label>
                    <input type="text" id="nombre" name="nombre">

                    <label for="Correo">Tu correo Electrónico: </label>
                    <input type="email" name="correo" id="correo">

                    <label for="mensaje">Mensaje: </label>
                    <textarea name="mensaje" id="mensaje" rows="5"></textarea>

                    <button type="submit">Enviar</button>

                </form>
            </section>
            <aside class="redes">
                <h4>SÍGUENOS</h4>
                <ul>
                    <li><img src="img/logos/linkedin.png">Linkedin</li>
                    <li> <img src="img/logos/facebook.png">Facebook</li>
                    <li><img src="img/logos/instagram.png">Instagram</li>
                    <li> <img src="img/logos/YT.png">YouTube</li>
                </ul>
            </aside>
        </main>
    </div>
    <!-- btn flotante del chatbot-->
    <button id="chatbot-toggle" onclick="toggleChat()">💬</button>

    <!-- Caja del chatbot -->
    <div id="chatbot-box" style="display: none;">
        <div id="chatbot-header">Aldexbot</div>
        <div id="chatbot-messages"></div>
        <div id="chatbot-input-container">
            <input type="text" id="chatbot-input" placeholder="Escribe tu mensaje..."
                onkeydown="if(event.key==='Enter') sendMessage()" />
            <button id="chatbot-send" onclick="sendMessage()">Enviar</button>
        </div>
    </div>

    <!-- Logica para el chatbot -->
    <script>
        let estado = "inicio";

        // Oculta el chat inmediatamente al cargar
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById("chatbot-box").style.display = "none";
        });

        // Mostrar/ocultar el chat
        function toggleChat() {
            const box = document.getElementById("chatbot-box");
            box.style.display = box.style.display === "none" ? "flex" : "none";
            document.getElementById("chatbot-messages").scrollTop =
                document.getElementById("chatbot-messages").scrollHeight;
        }

        // Enviar mensaje
        function sendMessage() {
            const input = document.getElementById("chatbot-input");
            const message = input.value.trim().toLowerCase();
            if (!message) return;

            const chat = document.getElementById("chatbot-messages");
            chat.innerHTML += `<p><strong>Tú:</strong> ${message}</p>`;

            const response = generarRespuesta(message);
            chat.innerHTML += `<p><strong>Aldexbot:</strong> ${response}</p>`;
            input.value = "";
            chat.scrollTop = chat.scrollHeight;
        }

        // Generar respuesta automática
        function generarRespuesta(msg) {
            if (estado === "inicio" && msg.includes("hola")) {
                estado = "menu1";
                return mostrarMenu();
            }

            if (estado === "menu1") {
                switch (msg) {
                    case "1":
                        return "Visita nuestros productos: <a href='productos.php'>Ver productos</a><br><br>" + mostrarMenu();
                    case "2":
                        return "📍 Visitanos  en el Jr.Computec N°380 tambien podras visualizar el mapa dandol en:  <a href='https://maps.google.com?q=TechStore' target='_blank'>Ver en Google Maps</a><br><br>" + mostrarMenu();
                    case "3":
                        return "🕘 Lunes a sábado, de 9:00 a.m. a 6:00 p.m.<br><br>" + mostrarMenu();
                    case "4":
                        return "💳 Aceptamos Yape, Plin, tarjetas y efectivo.<br><br>" + mostrarMenu();
                    case "5":
                        return "🧑 Chatea con un asesor: <a href='https://wa.me/51934075905' target='_blank'>WhatsApp</a><br><br>" + mostrarMenu();
                    case "6":
                        estado = "submenu6";
                        return mostrarSubmenu6();
                    default:
                        return "❌ Opción no válida. Intenta nuevamente:<br><br>" + mostrarMenu();
                }
            }

            if (estado === "submenu6") {
                switch (msg) {
                    case "1":
                        return "🔧 Asegúrate de que los cables estén bien conectados. Si no enciende, prueba otro enchufe.<br><br>" + mostrarSubmenu6();
                    case "2":
                        return "📦 Si tu compra no funciona, contáctanos con tu boleta o comprobante. Te ayudamos a gestionarlo.<br><br>" + mostrarSubmenu6();
                    case "3":
                        return "💻 Puedes instalar software desde sus sitios oficiales. Si necesitas ayuda, te guiamos con TeamViewer.<br><br>" + mostrarSubmenu6();
                    case "4":
                        return "🔁 Para devoluciones, contáctanos con tu comprobante y un asesor te atenderá.<br><br>" + mostrarSubmenu6();
                    case "5":
                        estado = "menu1";
                        return mostrarMenu();
                    default:
                        return "❌ Opción inválida en Soporte Técnico. Intenta otra vez:<br><br>" + mostrarSubmenu6();
                }
            }

            return "🤖 Escribe 'hola' para comenzar.<br><br>" + mostrarMenu();
        }

        // Menú principal
        function mostrarMenu() {
            estado = "menu1";
            return `¿En qué puedo ayudarte? Elige una opción:<br>
    1. Ver productos<br>
    2. Dirección<br>
    3. Horarios<br>
    4. Formas de pago<br>
    5. Hablar con un asesor<br>
    6. Soporte técnico`;
        }

        // Submenú de soporte técnico
        function mostrarSubmenu6() {
            return `🛠️ Soporte Técnico:<br>
    1. Tengo problemas con mi PC<br>
    2. Mi compra no funciona<br>
    3. Ayuda para instalar software<br>
    4. Quiero hacer una devolución<br>
    5. Volver al menú principal`;
        }
    </script>
</body>

</html>