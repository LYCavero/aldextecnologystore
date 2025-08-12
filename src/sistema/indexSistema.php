<?php
session_start();

if(isset($_GET["menu_seleccionado"])){
$_SESSION["menu_activo"] = $_GET["menu_seleccionado"];
}

$menuActivo = $_SESSION["menu_activo"] ?? "ninguno";

//Redirige al login si no está autenticado
if (!isset($_SESSION["id_usuario"])) {

    header("Location: login.php");
    exit();
}

include_once('../cls_conectar/cls_conexion.php');
$obj = new Conexion();
$conexion1 = $obj->getConectar();

$botonAgregarDisponible = ["usuarios","productos"];

$headersTabla = [];
$rowsTabla = "";
$modalTituloEditar = "";
$modalTituloAgregar = "";
$modalTituloEnviar = "";
$btnEditar = "<button class='btn-editar-usuario btn-editar'> <i class='fa-solid fa-pen fa-xl'></i> </button>";
$btnEliminar = "<button  class='btn-eliminar-usuario btn-eliminar'> <i class='fa-solid fa-trash fa-xl'></i> </button>";
$btnCorreo = "";
$idModal = "";

    if($menuActivo == "usuarios"){
        $sql1 = "select * from rol;";
        $stmt1 = $conexion1->prepare($sql1);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        $sql2 = "select * from estado_cuenta;";
        $stmt1 = $conexion1->prepare($sql2);
        $stmt1->execute();
        $result2 = $stmt1->get_result();

        $opcionesRol = "";
        while ($rol = $result1->fetch_array()) { 
            $opcionesRol = $opcionesRol . 
            "<option value='{$rol[0]}'>
                {$rol[1]}
            </option>;";
        }
        $opcionesEstado = "";
        while($estado = $result2->fetch_array()){
            $opcionesEstado = $opcionesEstado . 
            "<option value='{$estado[0]}'> 
                {$estado[1]} 
            </option>";
        }
    }elseif($menuActivo == "productos"){
        $sql1 = "select * from categoria_producto;";
        $stmt1 = $conexion1->prepare($sql1);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        $opcionesCategoria = "";
        while ($categoria = $result1->fetch_array()) { 
            $opcionesCategoria = $opcionesCategoria . 
            "<option value='{$categoria[0]}'>
                {$categoria[1]}
            </option>;";
        }

    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estilos-administrador-cabecera.css">
    <link rel="stylesheet" href="../css/estilos-menus.css">

    <title>Document</title>
</head>

<body>
    <div id="contenedor-principal" class="flex">
        <div class="fondo-cabecera">
            <div class="cabecera espacio-lateral">
                <div class="logo">
                    <img src="../img/logos/logo.webp">
                </div>
                <nav id="secciones" class="secciones">
                    <!--Traer secciones de la cabecera por rol de usuario -->
                    <?php 
                        $data = include '../api/obtener_menus_bd.php';
                        $menus = [];
                        if ($data && $data["estado"] === "ok") {
                            $menus = $data["respuesta"]["menus"];
                        }
                        if(count($menus) != 0) {
                            foreach ($menus as $menu) {
                    ?>
                        <a href="indexSistema.php?menu_seleccionado=<?php echo $menu["slug"] ?>"><?php echo $menu["titulo_mod"]?></a>
                    <?php    
                            }
                        }
                    ?>  
                </nav>
                <button class="inicio-sesion-boton" id="btn-logout">
                    <span id=rol-cuenta style="color: white; font-size: 16px; font-weight: bold;">
                        <?php 
                            echo $_SESSION["rol_usuario"];
                        ?>
                    </span>
                    <span>
                        <i class="fa-solid fa-user fa-3x" aria-hidden="true" style="color: white;"></i>
                    </span>
                </button>
            </div>
        </div>
        <div id="contenido" class="espacio-lateral">
            <section id="section-usuarios" class="section">
                <div class="cont-1 flex">
                    <div class="div-titulo">
                        <h1 class="h1-titulo">
                            <?php
                                if($menuActivo == "usuarios"){
                                    echo "USUARIOS";
                                } elseif($menuActivo == "productos") {
                                    echo "PRODUCTOS";
                                } elseif($menuActivo == "mensajes-de-contacto"){
                                    echo "MENSAJES DE CONTACTO";
                                } else {
                                    exit();
                                }
                            ?>
                        </h1>
                    </div>
                    <?php 
                        if(in_array($menuActivo,$botonAgregarDisponible)){
                    ?>
                    <button class="btn-agregar-usuario btn-agregar" id="btn-agregar-usuario">
                        <i class="fa-solid fa-plus"></i>
                        <span>
                            <?php
                                if($menuActivo == "usuarios"){
                                    echo "Agregar Usuario";
                                } elseif($menuActivo == "productos") {
                                    echo "Agregar Producto";
                                } else {
                                    exit();
                                }
                            ?>
                        </span>
                    </button>
                    <?php
                        }
                    ?>
                    <div class="cont-search flex">
                        <input id="search-usuarios" class="search" type="search" placeholder=
                        <?php
                            if($menuActivo == "usuarios"){
                                echo "'Bucar Usuarios'";
                            } elseif($menuActivo == "productos") {
                                echo "'Bucar Productos'";
                            } elseif($menuActivo == "mensajes-de-contacto"){
                                echo "'Buscar mensajes de Contacto'";
                            } else {
                                exit();
                            }
                        ?>
                        >
                        <button id="search-btn-usuarios" class="search-btn" type="submit">
                            <i class="fas fa-2x fa-search" style="color: #003DA5;"></i>
                        </button>
                    </div>
                </div>
                <div class="tb-scroll-x tb-scroll-y">
                    <table id=tb-usuarios class="tb">
                    <thead>
                        <tr>
                            <?php
                                $headersTabla = [];
                                if($menuActivo == "usuarios"){
                                    $headersTabla = ["Usuario","Rol","Contraseña","Estado","Acciones"];
                                } elseif($menuActivo == "productos") {
                                    $headersTabla = ["Descripción","Precio","Categoria","Stock","Imagen","Acciones"];
                                } elseif($menuActivo == "mensajes-de-contacto"){
                                    $headersTabla = ["Cliente","Mensaje","Correo","Estado","Acciones"];
                                }

                                foreach($headersTabla as $header){
                                    echo "<th>{$header}</th>";
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $btnEditar = "<button class='btn-editar-usuario btn-editar'> <i class='fa-solid fa-pen fa-xl'></i> </button>";
                            $btnEliminar = "<button  class='btn-eliminar-usuario btn-eliminar'> <i class='fa-solid fa-trash fa-xl'></i> </button>";
                            $btnEnviarCorreo = "<button class='btn-enviar-correo'> <i class='fa-solid fa-envelope fa-xl' aria-hidden='true'></i> </button>";
                            $datosBd = "";
                            $celdasOcultas = [];
                            $acciones = "";

                            if($menuActivo == "usuarios"){
                                $datosBd = include "../api/obtener_usuarios_bd.php";
                                $celdasOcultas = [0,2,5];
                                $acciones = "{$btnEditar}{$btnEliminar}";
                            } elseif($menuActivo == "productos") {
                                $datosBd = include "../api/obtener_productos_bd_php.php";
                                $celdasOcultas = [0,3];
                                $acciones = "{$btnEditar}{$btnEliminar}";
                            } elseif($menuActivo == "mensajes-de-contacto"){
                                $datosBd = include "../api/obtener_contactos_bd.php";
                                $celdasOcultas = [0,4];
                                $acciones = "{$btnEnviarCorreo}";
                            }

                            foreach ($datosBd["respuesta"] as $rows) {
                                echo "<tr>";
                                $index = 0;
                                foreach($rows as $columna){
                                    $class = in_array($index, $celdasOcultas) ? "class='oculto'" : "";

                                    if($menuActivo == "productos" && $index==6){
                                        echo "<td {$class}><img src='{$columna}'></td>";
                                    } else {
                                        echo "<td {$class}>$columna</td>";
                                    }
                                    $index++;
                                }
                                echo "<td><div class='flex' style='gap: 20px'>{$acciones}</div></td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                    </table>
                </div>
                <?php 
                    if($menuActivo == "usuarios"){
                        $idModal = "modal-usuarios";
                    } elseif($menuActivo == "productos") {
                        $idModal = "modal-productos";
                    } elseif($menuActivo == "mensajes-de-contacto") {
                        $idModal = "modal-contactos-2";
                    }
                ?>
                <?php 
                    if($menuActivo == "mensajes-de-contacto"){
                        $modalTituloVerMensaje = "Mensaje de Contacto";
                ?>
                    <div id="modal-contactos-1" class="modal flex oculto">
                        <div class="modal-contenido-contactos">
                            <div class="modal-cabecera flex">
                                <h2 id="modal-titulo-contactos">Mensaje de contacto</h2>
                                <button class="btn-modal-cerrar flex">
                                    <i class="fa-solid fa-2x fa-xmark" style="color:#ffff"></i>
                                </button>
                            </div>
                            <div class="modal-cuerpo">
                                <div class="form-grupo flex">
                                    <p><strong>Nombre:</strong> <span id="par-nombreCliente"></span></p>
                                </div>
                                <div class="form-grupo flex">
                                    <p><strong>Correo electrónico:</strong> <span id="par-correoCliente"></span></p>
                                </div>
                                <div class="form-grupo flex">
                                    <p><strong>Mensaje:</strong></p>
                                    <p id="par-mensaje" style="white-space: pre-wrap;"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                    }
                ?>
            <div id="<?php echo $idModal ?>" class="modal flex oculto">
                <div class="modal-contenido-usuarios">
                    <div class="modal-cabecera flex">
                        <h2 id="modal-titulo">
                        <?php 
                            if($menuActivo == "usuarios"){
                                $modalTituloAgregar = "Agregar Usuario";
                                $modalTituloEditar = "Editar Usuario";
                            }elseif($menuActivo == "productos") {
                                $modalTituloAgregar = "Agregar Producto";
                                $modalTituloEditar = "Editar Producto";
                            }elseif($menuActivo == "mensajes-de-contacto") {
                                $modalTituoMensajeContacto == "Mensaje de contacto";
                                $modalTituloEnviar = "Enviar Correo";
                            }
                        ?>
                        </h2>
                        <button id="btn-modal-cerrar" class="btn-modal-cerrar flex">
                            <i class="fa-solid fa-2x fa-xmark" style="color:#ffff"></i>
                        </button>
                    </div>
                    <div class="modal-cuerpo flex">
                        <form method="POST" id="form" class="form" action="../api/">
                            <?php 
                                if($menuActivo == "usuarios"){
                            ?>
                                <input class='oculto' type='text' name='txt-idUsuario' id='txt-idUsuario'>
                                <div class='form-grupo flex'>
                                    <label for='txt-usuario'>Usuario</label>
                                    <input type='text' name='txt-usuario' id='txt-usuario' placeholder='Ingrese un usuario'>
                                </div>
                                <div class='form-grupo flex'>
                                    <label for='txt-contraseña'>Contraseña</label>
                                    <input type='text' name='txt-contraseña' id='txt-contraseña' placeholder='Ingrese una contraseña' >
                                </div>
                                <div class='form-fila flex'>
                                    <div class='form-grupo flex'>
                                        <label>Rol</label>
                                        <select name='cbo-rol' id='cbo-rol' class='cbo'>
                                            <option value=''>[Seleccione Rol]</option>
                                            <?php 
                                                echo $opcionesRol;
                                            ?>
                                        </select>
                                    </div>
                                    <div id='cont-cbo-estado' class='form-grupo flex'>
                                        <label>Estado</label>
                                        <select name='cbo-estado' id='cbo-estado' class='cbo'>
                                            <option value=''>[Seleccione Estado]</option>
                                            <?php 
                                                echo $opcionesEstado;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class='modal-btn flex'>
                                    <button id='btn-form-guardar' class='btn-form-guardar'>Guardar</button>
                                </div>
                            <?php
                                } elseif ($menuActivo == "productos"){
                            ?>
                                <input class='oculto' name='txt-idProducto' type='text' id='txt-idProducto'>
                                <div class='form-grupo flex'>
                                    <label for='txt-descripcion'>Descripción</label>
                                    <input type='text' name='txt-descripcion' id='txt-descripcion' placeholder='Ingrese una descripción'>
                                </div>
                                <div class='form-grupo flex'>
                                    <label for='num-precio'>Precio</label>
                                    <input type='number' name='num-precio' id='num-precio' step='0.01' min='0' placeholder='Ingrese el precio'>
                                </div>
                                <div class='form-grupo flex'>
                                    <label for='num-stock'>Stock</label>
                                    <input type='number' name='num-stock' id='num-stock' min='0' placeholder='Ingrese el stock'>
                                </div>
                                <div class='form-fila flex'>
                                    <div class='form-grupo flex'>
                                        <label>Categoría</label>
                                        <select name='cbo-categoria-producto' id='cbo-categoria-producto' class='cbo'>
                                            <option value=''>[Seleccione Categoria]</option>
                                            <?php 
                                                echo $opcionesCategoria;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class='form-fila flex'>
                                    <div class='form-grupo flex'>
                                        <label for='fil-imagen'>Imagen</label>
                                        <input type='file' name='fil-imagen' id='fil-imagen'>
                                        <input type='hidden' name='hid-imagen' id='hid-imagen'>
                                    </div>
                                    <div id='cont-img' class='form-grupo flex cont-img'>
                                        <img id='img-preview' class='img-height oculto' >
                                    </div>
                                </div>
                                <div class='modal-btn flex'>
                                    <button id='btn-form-guardar' class='btn-form-guardar'>Guardar</button>
                                </div>
                            <?php 
                                } elseif ($menuActivo == "mensajes-de-contacto"){
                            ?>
                                <div class="form-grupo flex">
                                    <label for="txt-nombre-cliente">Cliente</label>
                                    <input type="text" name="txt-nombre-cliente" id="txt-nombre-cliente" readonly>
                                </div>
                                <div class="form-grupo flex">
                                    <label for="txt-correo-cliente">Para:</label>
                                    <input type="text" name="txt-correo-cliente" id="txt-correo-cliente" readonly>
                                </div>
                                <div class="form-grupo flex">
                                    <label for="txta-mensaje-cliente">Mensaje</label>
                                    <textarea name="txta-mensaje-cliente" id="txta-mensaje-cliente" placeholder="Ingrese un mensaje"></textarea>
                                </div>
                                <div class="modal-btn flex">
                                    <button id="btn-form-enviar" class="btn-form-guardar">Enviar</button>
                                </div>
                            <?php 
                                }
                            ?>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- <script type="module" src="../js/index.js"></script> -->
    <script src="https://kit.fontawesome.com/1d7c05e90c.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(){

            let modal = document.querySelector(".modal");
            
            const modalTitulo = document.getElementById("modal-titulo");
            const form = document.querySelector(".form");

            //Agregar oyente de click
            document.addEventListener("click", async function(e){

                //Boton cerrar del modal
                if(e.target.closest(".btn-modal-cerrar")){
                    modal.classList.add("oculto");

                    form.querySelectorAll("input, select").forEach(element => {
                        if (element.tagName === "SELECT") {
                            element.selectedIndex = 0;
                        } else {
                            element.value = "";
                        }
                    });

                    if(document.getElementById("img-preview")){
                        document.getElementById("img-preview").src = "";
                        document.getElementById("img-preview").classList.add("oculto");
                    }

                    form.action = "../api/";
                }

                //Botón agregar
                if(e.target.closest(".btn-agregar")){

                    modalTitulo.textContent = "<?php echo $modalTituloAgregar?>";
                    modal.classList.remove("oculto");

                    if(modal.id == "modal-usuarios"){
                        form.action = form.action + "agregar_usuario_bd.php";
                        document.getElementById("cont-cbo-estado").classList.add("oculto");
                    } else if(modal.id == "modal-productos"){
                        form.action = form.action + "agregar_producto_bd.php";
                        form.enctype = "multipart/form-data";
                    }
                }

                //Botón editar
                if(e.target.closest(".btn-editar")){
                    
                    modalTitulo.textContent = "<?php echo $modalTituloEditar?>";
                    const row = e.target.closest("tr").querySelectorAll("td");

                    if(modal.id == "modal-usuarios"){

                        let idUsuario = row[0].textContent.trim();
                        let usuario = row[1].textContent.trim();
                        let idRol = row[2].textContent.trim();
                        let contraseña = row[4].textContent.trim();
                        let idEstado = row[5].textContent.trim();

                        console.log(idUsuario,usuario,idRol,contraseña,idEstado);
                        
    
                        document.getElementById("txt-idUsuario").value = idUsuario;
                        document.getElementById("txt-usuario").value = usuario;
                        document.getElementById("cbo-rol").value = idRol.trim();
                        document.getElementById("txt-contraseña").value = contraseña;
                        document.getElementById("cbo-estado").value = idEstado.trim();     

                        form.action = form.action + "actualizar_usuario_bd.php";

                        modal.classList.remove("oculto");
                    } else if(modal.id == "modal-productos") {

                        let idProducto = row[0].textContent.trim();
                        let descripcion = row[1].textContent.trim();
                        let precio = row[2].textContent.trim();
                        let idCategoria = row[3].textContent.trim();
                        let stock = row[5].textContent.trim();

                        //Obtenemos la ruta relativa de la imagen
                        let srcImgProducto = row[6].querySelector("img").src ?? "";
                        let rutaUploads = "/uploads/";
                        let indiceRutaUploads = srcImgProducto.indexOf(rutaUploads);

                        let rutaRelativaImg = "";
                        if (indiceRutaUploads !== -1) {
                            rutaRelativaImg = ".." + srcImgProducto.substring(indiceRutaUploads);
                        } else {
                            console.error("No se encontró la ruta base en el src de la imagen");
                        }
    
                        document.getElementById("txt-idProducto").value = idProducto.trim();
                        document.getElementById("txt-descripcion").value = descripcion.trim();
                        document.getElementById("num-precio").value = precio.trim();
                        document.getElementById("num-stock").value = stock.trim();
                        document.getElementById("cbo-categoria-producto").value = idCategoria.trim();    
                        document.getElementById("hid-imagen").value = rutaRelativaImg; 
                        document.getElementById("img-preview").src = srcImgProducto;

                        document.getElementById("img-preview").classList.remove("oculto");

                        form.action = form.action + "actualizar_producto_bd.php";
                        form.enctype = "multipart/form-data";

                        modal.classList.remove("oculto");
                    }
                }

                //Botón eliminar
                if(e.target.closest(".btn-eliminar")){
                    const id = e.target.closest("tr").querySelectorAll("td")[0].textContent.trim();
                    let resultadoAlerta = await Swal.fire({
                        title: "Estás seguro?",
                        text: "No podrás revertir esto!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar"
                    });
                    
                    if (resultadoAlerta.isConfirmed) {
                        
                        let bodyPost = {};
                        let rutaEliminar = "";
                        let headers = "JSON";
                        if(modal.id == "modal-usuarios"){
                            bodyPost = {idUsuario: id}
                            rutaEliminar = "eliminar_usuario_bd.php";
                        } else if(modal.id == "modal-productos"){

                            const srcAbsolutoImg = e.target.closest("tr").querySelectorAll("td")[6].querySelector("img").src ?? "";
                            const rutaImagenes = "/uploads/";
                            const indiceRutaImagenes = srcAbsolutoImg.indexOf(rutaImagenes);

                            let rutaRelativaImg = "";
                            if (indiceRutaImagenes !== -1) {
                                rutaRelativaImg = ".." + srcAbsolutoImg.substring(indiceRutaImagenes);
                            } else {
                                console.error("No se encontró la ruta base en el src de la imagen");
                            }
                            bodyPost = {idProducto: id, imagenActual: rutaRelativaImg}
                            rutaEliminar = "eliminar_producto_bd.php";
                        }

                        apiEliminar(bodyPost,rutaEliminar,headers);
                    }
                }

                //Botón Correo
                if(e.target.closest(".btn-enviar-correo")){
                    modalTitulo.textContent = "<?php echo $modalTituloEnviar?>";
                    const celdasTabla = e.target.closest("tr").querySelectorAll("td");
                    if(modal.id.includes("modal-contactos")){
                        modal = document.getElementById("modal-contactos-2");

                        document.getElementById("txt-nombre-cliente").value = celdasTabla[1].textContent.trim();
                        document.getElementById("txt-correo-cliente").value = celdasTabla[3].textContent.trim();

                        form.action = form.action + "enviar_correo_soporte_prueba.php";

                        modal.classList.remove("oculto");
                    }
                    
                }

                //Celda Tabla
                if(e.target.tagName == "TD"){
                    modalTitulo.textContent = "<?php echo $modalTituloEnviar?>";
                    if(modal.id.includes("modal-contactos")){
                        const celdasTabla = e.target.closest("tr").querySelectorAll("td");
                        
                        document.getElementById("par-nombreCliente").textContent = celdasTabla[1].textContent.trim();
                        document.getElementById("par-correoCliente").textContent = celdasTabla[3].textContent.trim();
                        document.getElementById("par-mensaje").textContent = celdasTabla[2].textContent.trim();

                        modal = document.getElementById("modal-contactos-1");
                        modal.classList.remove("oculto");
                    }
                }

                //Botón enviar
                if(e.target == document.getElementById("btn-form-enviar")){
                    e.preventDefault();

                    const botonEnviarCorreo = document.getElementById("btn-form-enviar");
                    botonEnviarCorreo.classList.remove("btn-form-guardar")
                    botonEnviarCorreo.classList.add("enviando");
                    botonEnviarCorreo.disabled = true;
                    botonEnviarCorreo.textContent = "Enviando...";
                    document.getElementById("form").submit();
                }

                //Botón logout
                if(e.target.closest(".inicio-sesion-boton")){
                    window.location.href = "logoutControlador.php";
                }
            })

            //Agregar oyente de cambios
            document.addEventListener("change", function(e){
                if(e.target == document.getElementById("fil-imagen")){
                    const fileImg = document.getElementById("fil-imagen").files[0];
                    const previewImg = document.getElementById("img-preview");
                    if(fileImg){
                        previewImg.classList.remove("oculto");
                        previewImg.src = URL.createObjectURL(fileImg);
                    }else{
                        previewImg.classList.add("oculto");
                    }
                }
            });
        })

        function apiEliminar(bodyPost, rutaEliminar, headers){

            // const cuerpo = {
            //     method: "POST",
            //     headers: {}
            // }
            // if(headers === "JSON"){
            //     cuerpo.headers = {"Content-Type": "application/json"}
            //     cuerpo.body = JSON.stringify(bodyPost);
            // } else {
            //     cuerpo.body = bodyPost;
            // }

            fetch("../api/"+rutaEliminar ,{
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify(bodyPost)
            })
            // .then(respuesta => console.log(respuesta.json()))
            .then(
                function(){
                    window.location.href = "indexSistema.php";
                }
            )
        }

    </script>
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