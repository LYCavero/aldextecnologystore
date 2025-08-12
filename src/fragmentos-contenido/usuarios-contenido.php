<?php
// include("../seguridad/Autenticacion.php"); Ya no se coloca el include, porque el include
// se está colocando en el archivo controlador_fragmentos_contenido.php
// Auth::revisarRole("ADMINISTRADOR");

// include("../cls_conectar/cls_conexion.php");
// $obj = new Conexion();
// $conexion1 = $obj->getConectar();
// $conexion2 = $obj->getConectar();

$sql1 = "select * from rol;";
$stmt1 = $conexion1->prepare($sql1);
$stmt1->execute();

$result1 = $stmt1->get_result();
// $roles = $result -> fetch_all();

$sql2 = "select * from estado_cuenta;";
$stmt1 = $conexion1->prepare($sql2);
$stmt1->execute();

$result2 = $stmt1->get_result();

?>

<section id="section-usuarios" class="section">
    <div class="cont-1 flex">
        <div class="div-titulo">
            <h1 class="h1-titulo">USUARIOS</h1>
        </div>
        <button class="btn-agregar-usuario" id="btn-agregar-usuario">
            <i class="fa-solid fa-plus"></i>
            <span>Agregar Usuario</span>
        </button>
        <div class="cont-search flex">
            <input id="search-usuarios" class="search" type="search" placeholder="Buscar Usuario">
            <button id="search-btn-usuarios" class="search-btn" type="submit">
                <i class="fas fa-2x fa-search" style="color: #003DA5;"></i>
            </button>
        </div>
    </div>
    <div class="tb-scroll-x tb-scroll-y">
        <table id=tb-usuarios class="tb">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Contraseña</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    </div>
    <div id="modal-usuarios" class="modal flex oculto">
        <div class="modal-contenido-usuarios">
            <div class="modal-cabecera flex">
                <h2 id="modal-titulo-usuarios"></h2>
                <button id="btn-modal-cerrar" class="btn-modal-cerrar flex">
                    <i class="fa-solid fa-2x fa-xmark" style="color:#ffff"></i>
                </button>
            </div>
            <div class="modal-cuerpo flex">
                <form id="form-usuarios" class="form">
                    <div class="form-grupo flex">
                        <label for="txt-usuario">Usuario</label>
                        <input type="text" id="txt-usuario" placeholder="Ingrese un usuario">
                    </div>
                    <div class="form-grupo flex">
                        <label for="txt-contraseña">Contraseña</label>
                        <input type="text" id="txt-contraseña" placeholder="Ingrese una contraseña">
                    </div>
                    <div class="form-fila flex">
                        <div class="form-grupo flex">
                            <label>Rol</label>
                            <select id="cbo-rol" class="cbo">
                                <option value="">[Seleccione Rol]</option>

                                <?php while ($rol = $result1->fetch_array()) { ?>
                                    <option value="<?php echo $rol[0] ?>">
                                        <?php echo $rol[1] ?>
                                    </option>
                                <?php
                                };
                                ?>

                            </select>
                        </div>
                        <div id="cont-cbo-estado" class="form-grupo flex">
                            <label>Estado</label>
                            <select id="cbo-estado" class="cbo">
                                <option value="">[Seleccione Estado]</option>

                                <?php while ($estado = $result2->fetch_array()) { ?>
                                    <option value="<?php echo $estado[0] ?>">
                                        <?php echo $estado[1] ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-btn flex">
                        <button id="btn-form-guardar" class="btn-form-guardar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>