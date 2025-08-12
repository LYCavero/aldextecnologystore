import { alertaConfirmar, verificarRespuesta, verificarRespuestaSistema } from "../componentes/alertas.js";
import { renderizarContenido } from "../componentes/renderizarContenido.js";
import { deleteProducto, getMenuProductos, getProductos, postProducto, putProducto} from "../utils/apiServicios.js";

export async function inicializarVista(contenedor) {

    console.log("Inicializando Vista: Productos...");

    // obtiene el bloque HTML del menú de productos
    const datos = await getMenuProductos();
    verificarRespuestaSistema(datos);

    const contenidoHtml = datos.respuesta;

    contenedor.removeAttribute("style");
    contenedor.classList.remove("flex");

    await renderizarContenido(contenedor, contenidoHtml);

    const btnAgregarProducto = document.getElementById("btn-agregar-producto");
    const txtBuscador = document.getElementById("search-productos");

    const tbProductos = document.querySelector("#tb-productos tbody");
    const btnCerrarModal = document.getElementById("btn-modal-cerrar");
    const btnFormGuardar = document.getElementById("btn-form-guardar");
    const inputImg = document.getElementById("fil-imagen");
    const contImg = document.getElementById("cont-img");
    const imgPreview = document.getElementById("img-preview");

    agregarAccionInputImagen(contImg, inputImg, imgPreview);
    agregarAccionBtnAgregarProducto(btnAgregarProducto);
    agregarAccionBtnCerrarModal(btnCerrarModal);

    await llenarTabla(tbProductos);
    agregarAccionTxtBuscador(txtBuscador, tbProductos);

    // Trae los botones de editar y eliminar de todas las filas de la tabla
    // const listaBtnEditarProducto = document.querySelectorAll(".btn-editar-producto");
    // const listaBtnEliminarProducto = document.querySelectorAll(".btn-eliminar-producto");

    // agregarAccionBtnActualizarProducto(listaBtnEditarProducto);
    // agregarAccionBtnEliminarProducto(listaBtnEliminarProducto, tbProductos);
    await agregarAccionBtnFormGuardar(btnFormGuardar, tbProductos);



}

/**
 * 
 * @param {HTMLButtonElement} btnAgregarProducto 
 */
function agregarAccionBtnAgregarProducto(btnAgregarProducto) {

    btnAgregarProducto.addEventListener("click", function () {
        const tituloModal = document.getElementById("modal-titulo-productos");
        const txtDescripcion = document.getElementById("txt-descripcion");
        const numPrecio = document.getElementById("num-precio");
        const numStock = document.getElementById("num-stock");
        const cboCategoriaProducto = document.getElementById("cbo-categoria-producto");
        const filImagen = document.getElementById("fil-imagen");
        // const hidImagen = document.getElementById("hid-imagen");
        // const imgPreview = document.getElementById("img-preview");



        tituloModal.innerHTML = "Agregar Producto";
        // Limpia el formulario del modal
        txtDescripcion.value = "";
        numPrecio.value = "";
        numStock.value = "";
        cboCategoriaProducto.value = "";
        filImagen.value = "";
        // hidImagen.value = "";
        // imgPreview.src = "";
        // imgPreview.classList.add("oculto");


        const btnFormGuardar = document.getElementById("btn-form-guardar");

        btnFormGuardar.dataset.origen = "agregar";



        mostrarModalProducto();
    });

}

/**
 * 
 * @param {HTMLDivElement} contImg
 * @param {HTMLInputElement} inputImagen 
 * @param {HTMLImageElement} imgPreview
 */
function agregarAccionInputImagen(contImg, inputImagen, imgPreview) {

    inputImagen.addEventListener("change", () => {
        console.log("se cambió la imagen");
        const archivo = inputImagen.files[0];
        console.log(archivo);

        if (archivo) {
            console.log("Si se seleccionó un archivo");
            
            imgPreview.classList.remove("oculto");
            const urlTemporal = URL.createObjectURL(archivo);
            console.log(urlTemporal);

            imgPreview.src = urlTemporal;

            // URL.revokeObjectURL(urlTemporal);
        } else {
            console.log("No se seleccionó un archivo");
            
            const hidImagen = document.getElementById("hid-imagen");
            if (hidImagen.value != "") {

                imgPreview.src = hidImagen.value;
            } else {

                imgPreview.classList.add("oculto");
                imgPreview.src = ""; // Limpiar vista previa si no hay archivo
            }

        }
    });
}

/**
 * 
 * @param {HTMLButtonElement} btnFormGuardar 
 * @param {HTMLTableElement} tbProductos
 */
async function agregarAccionBtnFormGuardar(btnFormGuardar, tbProductos) {

    btnFormGuardar.addEventListener("click", async (e) => {

        // Evita que al presionar el botón de Guardar del modal se recargue 
        // la pagina
        e.preventDefault();

        // Obtiene los valores de los formularios, y lo convierte en un json
        const inputImagen = document.getElementById("fil-imagen");
        const archivoImagen = inputImagen.files[0];

        const datosFormulario = new FormData();

        datosFormulario.append("descripcion", document.getElementById("txt-descripcion").value);
        datosFormulario.append("precio", document.getElementById("num-precio").value);
        datosFormulario.append("stock", document.getElementById("num-stock").value);
        datosFormulario.append("idCategoria", document.getElementById("cbo-categoria-producto").value);
        
        if (archivoImagen) {
            datosFormulario.append("imagen", archivoImagen);
        }

        // Verifica que botón se presionó, si el botón de agregar producto o
        // el botón de editar producto
        if (btnFormGuardar.dataset.origen === "agregar") {

            // Obtiene la respuesta si se agregó el producto a la base de datos
            const datos = await postProducto(datosFormulario);

            // y verifica si todo fue okey o hubo algún error
            verificarRespuesta(datos);
            console.log(datos);

        } else if (btnFormGuardar.dataset.origen === "editar") {

            datosFormulario.append("imagenActual", document.getElementById("hid-imagen").value);
            datosFormulario.append("idProducto", document.getElementById("txt-descripcion").dataset.idProducto);


            const datos = await putProducto(datosFormulario);

            verificarRespuesta(datos);
            console.log(datos);


            // agrega tambien el idProducto y idEstado al objeto "datosFormulario"
            // datosFormulario.idProducto = document.getElementById("txt-producto").dataset.idProducto;
            // datosFormulario.idEstado = document.getElementById("cbo-estado").value;

            // const datos = await putProducto(datosFormulario);

            // verificarRespuesta(datos);


        }

        for (const [clave, valor] of datosFormulario.entries()) {
            console.log(`${clave}: ${valor}`);
        }

        await llenarTabla(tbProductos);

        mostrarModalProducto(false);
    })
}

/**
 * 
 * @param {HTMLButtonElement[]} listaBtnEditarProducto 
 */
function agregarAccionBtnActualizarProducto(listaBtnEditarProducto) {

    // Le asignará el evento a todos los botones de actualizar
    listaBtnEditarProducto.forEach((btnEditarProducto) => {

        btnEditarProducto.addEventListener("click", function () {

            const tituloModal = document.getElementById("modal-titulo-productos");
            tituloModal.innerHTML = "Editar Producto";

            // Obtiene la fila de donde se dió click al botón de editar
            const filaDatos = btnEditarProducto.closest("tr").querySelectorAll("td");

            // Obtiene los valores de las celdas de la fila obtenida
            const idProductoFila = filaDatos[0].dataset.idProducto;
            const productoFila = filaDatos[0].textContent.trim();
            const precioFila = filaDatos[1].textContent.trim();
            const idCategoriaProductoFila = filaDatos[2].dataset.idCategoriaProducto;
            const stockFila = filaDatos[3].textContent.trim();

            const srcAbsoluto = filaDatos[4].querySelector("img").src ?? "";
            const rutaUploads = "/uploads/";
            const indiceRutaUploads = srcAbsoluto.indexOf(rutaUploads);

            let rutaRelativa = "";
            if (indiceRutaUploads !== -1) {
                rutaRelativa = ".." + srcAbsoluto.substring(indiceRutaUploads);
            } else {

                console.error("No se encontró la ruta base en el src de la imagen");
            }

            console.log("Ruta relativa obtenida: " + rutaRelativa);


            const txtDescripcion = document.getElementById("txt-descripcion");
            const numPrecio = document.getElementById("num-precio");
            const numStock = document.getElementById("num-stock");
            const cboCategoriaProducto = document.getElementById("cbo-categoria-producto");
            const hidImagen = document.getElementById("hid-imagen");
            const imgPreview = document.getElementById("img-preview");

            const btnFormGuardar = document.getElementById("btn-form-guardar");

            // Se le asignan los valores de la fila al modal editar producto
            txtDescripcion.value = productoFila;
            numPrecio.value = precioFila;
            numStock.value = stockFila;
            cboCategoriaProducto.value = idCategoriaProductoFila;
            hidImagen.value = rutaRelativa;
            txtDescripcion.dataset.idProducto = idProductoFila;
            imgPreview.src = srcAbsoluto;

            imgPreview.classList.remove("oculto");

            // Se le asigna la accion que se va a realizar
            btnFormGuardar.dataset.origen = "editar";

            mostrarModalProducto();
            // mostrarImagenCelda();
        })
    });
}

/**
 * 
 * @param {HTMLButtonElement[]} listaBtnEliminarProducto 
 * @param {HTMLTableElement} tbProductos 
 */
function agregarAccionBtnEliminarProducto(listaBtnEliminarProducto, tbProductos) {

    listaBtnEliminarProducto.forEach((btnEliminarProducto) => {
        btnEliminarProducto.addEventListener("click", async function () {

            const result = await alertaConfirmar();

            const srcAbsoluto = btnEliminarProducto.closest("tr").querySelectorAll("td")[4].querySelector("img").src ?? "";
            const rutaUploads = "/uploads/";
            const indiceRutaUploads = srcAbsoluto.indexOf(rutaUploads);

            let rutaRelativa = "";
            if (indiceRutaUploads !== -1) {
                rutaRelativa = ".." + srcAbsoluto.substring(indiceRutaUploads);
            } else {

                console.error("No se encontró la ruta base en el src de la imagen");
            }

            if (result.isConfirmed) {

                const datosFormulario = {
                    idProducto: btnEliminarProducto.closest("tr").querySelectorAll("td")[0].dataset.idProducto,
                    imagenActual: rutaRelativa
                }

                const datos = await deleteProducto(datosFormulario);

                verificarRespuesta(datos);

                if (datos.estado === "ok") {

                    await llenarTabla(tbProductos);

                }


            }
        })
    });
}

/**
 * 
 * @param {HTMLTableElement} tbProductos
 */
async function llenarTabla(tbProductos) {

    // Obtiene la lista de productos de la base de datos
    const datos = await getProductos();

    verificarRespuesta(datos, false, true);

    // Limpia las filas de la tabla
    tbProductos.innerHTML = "";

    // Recorre las filas obtenidas de la base de datos una por una
    datos.respuesta.forEach(productoBD => {

        // Crea una etiqueta fila
        const filaNueva = document.createElement("tr");

        // A la fila creada se le insertan los datos de las filas obtenidas de la base de datos
        filaNueva.innerHTML = `
                            <td data-id-producto="${productoBD.id_producto}" class="td-producto" >${productoBD.descripcion}</td>
                            <td class="td-precio" >${productoBD.precio}</td>
                            <td data-id-categoria-producto="${productoBD.id_categoria_producto}" class="td-categoria" >${productoBD.categoria}</td>
                            <td class="td-stock" >${productoBD.stock}</td>
                            <td class="td-imagen" ><img src="${productoBD.imagen}"></td>
                            <td class="td-accion">
                                <div class="flex" style="gap: 20px">
                                    <button class="btn-editar-producto btn-editar">
                                        <i class="fa-solid fa-pen fa-xl"></i>
                                    </button>
                                    <button  class="btn-eliminar-producto btn-eliminar">
                                        <i class="fa-solid fa-trash fa-xl"></i>
                                    </button>
                                </div>
                            </td>
                        `;

        // Una vez la fila creada ya tiene los datos insertados, esta es insertada en la tabla 
        // de la vista de productos, y así sucesivamente
        tbProductos.appendChild(filaNueva);

    });

    console.log("Cantidad de filas:", tbProductos.rows.length);

    // Se obtiene nuevamente los botones de las filas de la tabla, ya que esta función se va
    // a utilizar cada vez que se agrega, actualiza y elimina un producto
    const nuevaListaBtnEditarProducto = document.querySelectorAll(".btn-editar-producto");
    const nuevaListaBtnEliminarProducto = document.querySelectorAll(".btn-eliminar-producto");

    // Se le asignan nuevamente las acciones a los botones de la columna "ACCIONES"
    agregarAccionBtnActualizarProducto(nuevaListaBtnEditarProducto);
    agregarAccionBtnEliminarProducto(nuevaListaBtnEliminarProducto, tbProductos);

}

/**
 * 
 * @param {HTMLInputElement} txtBuscador
 * @param {HTMLTableElement} tbProductos
 */
function agregarAccionTxtBuscador(txtBuscador, tbProductos) {
    if (txtBuscador && !txtBuscador.placeholder) {
        txtBuscador.placeholder = "Buscar Producto";
    }

    txtBuscador.addEventListener("input", function () {
        const textBuscar = txtBuscador.value.toLocaleLowerCase().trim();

        let vueltasFila = 0;
        let i = 0;
        if (textBuscar !== "") {
            console.log(textBuscar);
            tbProductos.querySelectorAll("tr").forEach(tbFila => {
                vueltasFila++;
                let verdadero = false;

                tbFila.querySelectorAll("td").forEach(celda => {
                    const textCelda = celda.textContent.toLowerCase();
                    const coincide = textCelda.includes(textBuscar);

                    // debugger;

                    if (verdadero) {
                        return;
                    }

                    if (coincide) {
                        verdadero = true;
                        console.log(coincide);
                        i++;
                        tbFila.style.display = "";
                    } else {
                        // tbFila.style.display = "none";
                    }


                })

                if (!verdadero) {
                    tbFila.style.display = "none";
                }
            }
            );


        } else {

            tbProductos.querySelectorAll("tr").forEach(tbFila => {
                tbFila.style.display = "";
            });

            console.log("el buscador está vacío");
        }
        console.log("Vuentas filas: ", vueltasFila);

        console.log("Vueltas en total:", i);

    })
}

/**
 * 
 * @param {HTMLButtonElement} btnCerrarModal 
 */
function agregarAccionBtnCerrarModal(btnCerrarModal) {
    btnCerrarModal.addEventListener("click", function () {

        mostrarModalProducto(false);

        // const hidImagen = document.getElementById("hid-imagen");
        // const imgPreview = document.getElementById("img-preview");

        // hidImagen.value = "";
        // imgPreview.src = "";
        // imgPreview.classList.add("oculto");
    })
}


function mostrarModalProducto(accion = true) {

    const modalProductos = document.getElementById("modal-productos");

    // Si es true, muestra el modal
    if (accion) {
        modalProductos.classList.remove("oculto");
        const hidImagen = document.getElementById("hid-imagen");
        const imgPreview = document.getElementById("img-preview");
        hidImagen.value = "";
        imgPreview.scr = "";
    } else {
        // Si es falso, oculta el modal
        modalProductos.classList.add("oculto");

        const tituloModal = document.getElementById("modal-titulo-productos");
        const txtDescripcion = document.getElementById("txt-descripcion");
        const btnFormGuardar = document.getElementById("btn-form-guardar");
        const hidImagen = document.getElementById("hid-imagen");
        const imgPreview = document.getElementById("img-preview");

        // y limpia el titulo del formulario
        tituloModal.innerHTML = "";
        hidImagen.value = "";
        imgPreview.scr = "";
        txtDescripcion.removeAttribute("data-id-producto");
        btnFormGuardar.removeAttribute("data-origen");
    }
}

function mostrarImagenCelda(accion = true) {
    const divImg = document.getElementById("cont-img");

    if (accion) {
        divImg.classList.remove("oculto");
    } else {
        divImg.classList.add("oculto");
    }
}