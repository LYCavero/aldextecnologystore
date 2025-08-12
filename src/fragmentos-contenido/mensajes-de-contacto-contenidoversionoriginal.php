<?php
Auth::revisarRole("SOPORTE");

// $sql1 = "select * from rol;";
// $stmt1 = $conexion1->prepare($sql1);
// $stmt1->execute();

// $result1 = $stmt1->get_result();
// // $roles = $result -> fetch_all();

// $sql2 = "select * from estado_cuenta;";
// $stmt1 = $conexion1->prepare($sql2);
// $stmt1->execute();

// $result2 = $stmt1->get_result();

?>

<section id="section-mensajes-de-contacto" class="section">
    <div class="cont-1 flex">
        <div class="div-titulo">
            <h1 class="h1-titulo">MENSAJES DE CONTACTO</h1>
        </div>
        <!-- <button class="btn-agregar-usuario" id="btn-agregar-usuario">
            <i class="fa-solid fa-plus"></i>
            <span>Agregar Usuario</span>
        </button> -->
        <div class="cont-search flex">
            <input id="search-contactos" class="search" type="search" placeholder="Buscar mensajes de Contacto">
            <button id="search-btn-contactos" class="search-btn" type="submit">
                <i class="fas fa-2x fa-search" style="color: #003DA5;"></i>
            </button>
        </div>
    </div>
    <div class="tb-scroll-x tb-scroll-y">
        <table id=tb-mensajes-de-contacto class="tb">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Mensaje</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
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
    <div id="modal-contactos-2" class="modal flex oculto">
        <div class="modal-contenido-contactos">
            <div class="modal-cabecera flex">
                <h2 id="modal-titulo-contactos">Enviar correo</h2>
                <button class="btn-modal-cerrar flex">
                    <i class="fa-solid fa-2x fa-xmark" style="color:#ffff"></i>
                </button>
            </div>
            <div class="modal-cuerpo flex">
                <form id="form-contactos" class="form">
                    <div class="form-grupo flex">
                        <label for="txt-correo-cliente">Para:</label>
                        <input type="text" id="txt-correo-cliente" disabled>
                    </div>
                    <div class="form-grupo flex">
                        <label for="txt-nombre-cliente">Cliente</label>
                        <input type="text" id="txt-nombre-cliente" disabled>
                    </div>
                    <div class="form-grupo flex">
                        <label for="txta-mensaje-cliente">Mensaje</label>
                        <textarea id="txta-mensaje-cliente" placeholder="Ingrese un mensaje"></textarea>
                    </div>
                    <div class="modal-btn flex">
                        <button id="btn-form-enviar" class="btn-form-guardar">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>