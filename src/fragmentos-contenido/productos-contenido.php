<?php
Auth::revisarRole("VENDEDOR");

$sql1 = "select * from categoria_producto;";
$stmt1 = $conexion1->prepare($sql1);
$stmt1->execute();

$result1 = $stmt1->get_result();

?>

<section id="section-productos" class="section">
    <div class="cont-1 flex">
        <div class="div-titulo">
            <h1 class="h1-titulo">PRODUCTOS</h1>
        </div>
        <button class="btn-agregar-producto" id="btn-agregar-producto">
            <i class="fa-solid fa-plus"></i>
            <span>Agregar Producto</span>
        </button>
        <div class="cont-search flex">
            <input id="search-productos" class="search" type="search" placeholder="Buscar Producto">
            <button id="search-btn-productos" class="search-btn" type="submit">
                <i class="fas fa-2x fa-search" style="color: #003DA5;"></i>
            </button>
        </div>
    </div>
    <div class="tb-scroll-x tb-scroll-y">
        <table id=tb-productos class="tb">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Categoria</th>
                    <th>Stock</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div id="modal-productos" class="modal flex oculto"> <!-- oculto -->
        <div class="modal-contenido-productos">
            <div class="modal-cabecera flex">
                <h2 id="modal-titulo-productos"></h2>
                <button id="btn-modal-cerrar" class="btn-modal-cerrar flex">
                    <i class="fa-solid fa-2x fa-xmark" style="color:#ffff"></i>
                </button>
            </div>
            <div class="modal-cuerpo flex">
                <form id="form-productos" class="form">
                    <div class="form-grupo flex">
                        <label for="txt-descripcion">Descripción</label>
                        <input type="text" id="txt-descripcion" placeholder="Ingrese una descripción">
                    </div>
                    <div class="form-grupo flex">
                        <label for="num-precio">Precio</label>
                        <input type="" id="num-precio" min="0" placeholder="Ingrese el precio">
                    </div>
                    <div class="form-grupo flex">
                        <label for="num-stock">Stock</label>
                        <input type="number" id="num-stock" min="0" placeholder="Ingrese el stock">
                    </div>
                    <div class="form-fila flex">
                        <div class="form-grupo flex">
                            <label>Categoría</label>
                            <select id="cbo-categoria-producto" class="cbo">
                                <option value="">[Seleccione Categoria]</option>

                                <?php while ($rol = $result1->fetch_array()) { ?>
                                    <option value="<?php echo $rol[0] ?>">
                                        <?php echo $rol[1] ?>
                                    </option>
                                <?php
                                };
                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-fila flex">
                        <div class="form-grupo flex">
                            <label for="fil-imagen">Imagen</label>
                            <input type="file" name="seleccionador" id="fil-imagen">
                            <input type="hidden" id="hid-imagen">
                        </div>
                        <div id="cont-img" class="form-grupo flex cont-img">
                            <img id="img-preview" class="img-height oculto" >
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