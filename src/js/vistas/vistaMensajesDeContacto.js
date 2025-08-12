import { verificarRespuesta, verificarRespuestaSistema } from "../componentes/alertas.js";
import { renderizarContenido } from "../componentes/renderizarContenido.js";
import { getMensajesDeContactos, getMenuMensajesDeContacto, postCorreoCliente, putContacto, putEstadoContacto } from "../utils/apiServicios.js";

export async function inicializarVista(contenedor) {

    console.log("Inicializando Vista: Mensajes de Contacto...");

    // obtiene el bloque HTML del menú de mensajes-de-contacto
    const datos = await getMenuMensajesDeContacto();
    verificarRespuestaSistema(datos);

    const contenidoHtml = datos.respuesta;

    contenedor.removeAttribute("style");
    contenedor.classList.remove("flex");

    await renderizarContenido(contenedor, contenidoHtml);

    // const btnAgregarProducto = document.getElementById("btn-agregar-contacto");
    const txtBuscador = document.getElementById("search-mensajes-de-contacto");

    const tbMensajesDeContacto = document.querySelector("#tb-mensajes-de-contacto tbody");
    const listaBtnCerrarModal = document.querySelectorAll(".btn-modal-cerrar");
    const btnFormEnviar = document.getElementById("btn-form-enviar");
    const inputImg = document.getElementById("fil-imagen");
    const contImg = document.getElementById("cont-img");
    const imgPreview = document.getElementById("img-preview");
    const modalContactos1 = document.getElementById("modal-contactos-1");
    const modalContactos2 = document.getElementById("modal-contactos-2");

    // agregarAccionInputImagen(contImg, inputImg, imgPreview);
    agregarAccionBtnCerrarModal(listaBtnCerrarModal, modalContactos1, modalContactos2);

    await llenarTabla(tbMensajesDeContacto);
    // agregarAccionTxtBuscador(txtBuscador, tbMensajesDeContacto);

    // Trae los botones de editar y eliminar de todas las filas de la tabla
    // const listaBtnEditarProducto = document.querySelectorAll(".btn-editar-contacto");
    // const listaBtnEliminarProducto = document.querySelectorAll(".btn-eliminar-contacto");

    // agregarAccionBtnActualizarProducto(listaBtnEditarProducto);
    // agregarAccionBtnEliminarProducto(listaBtnEliminarProducto, tbMensajesDeContacto);
    await agregarAccionBtnFormEnviar(btnFormEnviar, tbMensajesDeContacto);



}

/**
 * 
 * @param {HTMLTableElement} tbMensajesDeContacto
 */
async function llenarTabla(tbMensajesDeContacto) {

    // Obtiene la lista de productos de la base de datos
    const datos = await getMensajesDeContactos();

    verificarRespuesta(datos, false, true);

    // Limpia las filas de la tabla
    tbMensajesDeContacto.innerHTML = "";

    // Recorre las filas obtenidas de la base de datos una por una
    datos.respuesta.forEach(contactoBD => {

        // Crea una etiqueta fila
        const filaNueva = document.createElement("tr");

        // A la fila creada se le insertan los datos de las filas obtenidas de la base de datos
        filaNueva.innerHTML = `
                            <td data-id-contacto="${contactoBD.id_contacto}" class="td-nombre" >${contactoBD.cliente}</td>
                            <td class="td-mensaje" >${contactoBD.mensaje}</td>
                            <td class="td-correro-electronico" >${contactoBD.correo_electronico}</td>
                            <td data-id-estado-contacto="${contactoBD.id_estado_contacto}" class="td-precio" >${contactoBD.estado_contacto}</td>
                            <td class="td-accion">
                                <div class="flex" style="gap: 20px">
                                    <button class="btn-enviar-correo btn-editar">
                                        <i class="fa-solid fa-envelope fa-xl"></i>
                                    </button>
                                </div>
                            </td>
                        `;

        // Una vez la fila creada ya tiene los datos insertados, esta es insertada en la tabla 
        // de la vista de productos, y así sucesivamente
        tbMensajesDeContacto.appendChild(filaNueva);

    });

    console.log("Cantidad de filas:", tbMensajesDeContacto.rows.length);

    const nuevaListaTrMensajeDeContacto = document.querySelectorAll("#tb-mensajes-de-contacto tbody tr");
    const listaBtnEnviarCorreo = document.querySelectorAll(".btn-enviar-correo");
    const modalContactos2 = document.getElementById("modal-contactos-2");

    agregarAccionFilaTablaMensajesDeContacto(nuevaListaTrMensajeDeContacto);
    agregarAccionBtnEnviarCorreo(listaBtnEnviarCorreo, modalContactos2);

    // Se obtiene nuevamente los botones de las filas de la tabla, ya que esta función se va
    // a utilizar cada vez que se agrega, actualiza y elimina un contacto
    // const nuevaListaBtnEditarProducto = document.querySelectorAll(".btn-editar-contacto");
    // const nuevaListaBtnEliminarProducto = document.querySelectorAll(".btn-eliminar-contacto");

    // Se le asignan nuevamente las acciones a los botones de la columna "ACCIONES"
    // agregarAccionBtnActualizarProducto(nuevaListaBtnEditarProducto);
    // agregarAccionBtnEliminarProducto(nuevaListaBtnEliminarProducto, tbMensajesDeContacto);

}

/**
 * 
 * @param {Array} nuevaListaTrMensajeDeContacto 
 */
function agregarAccionFilaTablaMensajesDeContacto(nuevaListaTrMensajeDeContacto) {

    const modalContactos1 = document.getElementById("modal-contactos-1");

    nuevaListaTrMensajeDeContacto.forEach(trMensajeDeContacto => {
        const tdFilaSeleccionada = trMensajeDeContacto.querySelectorAll("td");

        [0, 1, 2, 3].forEach(index => {
            tdFilaSeleccionada[index].addEventListener("click", async function () {
                modalContactos1.classList.remove("oculto");

                document.getElementById("par-nombreCliente").textContent = tdFilaSeleccionada[0].textContent;
                document.getElementById("par-mensaje").textContent = tdFilaSeleccionada[1].textContent;
                document.getElementById("par-correoCliente").textContent = tdFilaSeleccionada[2].textContent;

                mostrarModalContacto1(modalContactos1);

                const datosFormulario = {
                    idContacto: tdFilaSeleccionada[0].dataset.idContacto
                }

                console.log("Voy a enviar :", datosFormulario);

                const datos = await putEstadoContacto(datosFormulario);

                verificarRespuesta(datos, false);

            });
        });
    });
}

/**
 * 
 * @param {HTMLButtonElement} listaBtnCerrarModal 
 * @param {HTMLDivElement} modalContactos1 
 * @param {HTMLDivElement} modalContactos2
 */
function agregarAccionBtnCerrarModal(listaBtnCerrarModal, modalContactos1, modalContactos2) {

    listaBtnCerrarModal.forEach((btnCerrarModal) => {

        btnCerrarModal.addEventListener("click", async function () {

            mostrarModalContacto1(modalContactos1, false);
            mostrarModalContacto2(modalContactos2, false);

            const tbMensajesDeContacto = document.querySelector("#tb-mensajes-de-contacto tbody");
            await llenarTabla(tbMensajesDeContacto);
        })
    });

}

/**
 * 
 * @param {HTMLButtonElement[]} listaBtnEnviarCorreo 
 * @param {HTMLDivElement} modalContactos2
 */
function agregarAccionBtnEnviarCorreo(listaBtnEnviarCorreo, modalContactos2) {

    listaBtnEnviarCorreo.forEach(btnEnviarCorreo => {
        btnEnviarCorreo.addEventListener("click", async function () {

            const filaDatos = btnEnviarCorreo.closest("tr").querySelectorAll("td");

            const correoCliente = document.getElementById("txt-correo-cliente");
            const nombreCliente = document.getElementById("txt-nombre-cliente");
            const btnFormEnviar = document.getElementById("btn-form-enviar");

            correoCliente.value = filaDatos[2].textContent.trim();
            nombreCliente.value = filaDatos[0].textContent.trim();
            btnFormEnviar.dataset.idContacto = filaDatos[0].dataset.idContacto;

            mostrarModalContacto2(modalContactos2);
        })
    });
}

/**
 * 
 * @param {HTMLDivElement} modalContactos1 
 */
function mostrarModalContacto1(modalContactos1, accion = true) {

    // Si es true, muestra el modal
    if (accion) {

        modalContactos1.classList.remove("oculto");

    } else {
        // Si es falso, oculta el modal
        modalContactos1.classList.add("oculto");

        document.getElementById("par-nombreCliente").textContent = "";
        document.getElementById("par-mensaje").textContent = "";
        document.getElementById("par-correoCliente").textContent = "";
    }
}

/**
 * 
 * @param {HTMLDivElement} modalContactos2 
 */
function mostrarModalContacto2(modalContactos2, accion = true) {

    // Si es true, muestra el modal
    if (accion) {

        modalContactos2.classList.remove("oculto");

    } else {
        // Si es falso, oculta el modal
        modalContactos2.classList.add("oculto");

        document.getElementById("txt-nombre-cliente").textContent = "";
        document.getElementById("txta-mensaje-cliente").textContent = "";
        document.getElementById("txt-correo-cliente").textContent = "";
        document.getElementById("btn-form-enviar").dataset.idContacto = "";

    }
}

/**
 * 
 * @param {HTMLButtonElement} btnFormEnviar 
 * @param {HTMLTableElement} tbMensajesDeContacto
 */
async function agregarAccionBtnFormEnviar(btnFormEnviar, tbMensajesDeContacto) {

    btnFormEnviar.addEventListener("click", async (e) => {

        // Evita que al presionar el botón de Guardar del modal se recargue 
        // la pagina
        e.preventDefault();

        btnFormEnviar.classList.remove("btn-form-guardar")
        btnFormEnviar.classList.add("enviando");
        btnFormEnviar.disabled = true;
        btnFormEnviar.textContent = "Enviando...";

        const idContactoFila = btnFormEnviar.dataset.idContacto;

        // const datosFormularioCorreo = {
        //     correoCliente: document.getElementById("txt-correo-cliente").value,
        //     nombreCliente: document.getElementById("txt-nombre-cliente").value,
        //     mensajeCliente: document.getElementById("txta-mensaje-cliente").value
        // }

        const formData = new FormData();
        formData.append('correoCliente', document.getElementById('txt-correo-cliente').value);
        formData.append('nombreCliente', document.getElementById('txt-nombre-cliente').value);
        formData.append('mensajeCliente', document.getElementById('txta-mensaje-cliente').value);

        // Adjunta el archivo si hay uno seleccionado
        const inputArchivo = document.getElementById('file-pdf');
        if (inputArchivo.files.length > 0) {
            formData.append('file-pdf', inputArchivo.files[0]);
            console.log("Si se envía el pdf en el formData");
            
        }

        // Obtiene los valores de los formularios, y lo convierte en un json

        console.log("Voy a enviar datos correo:", formData);

        const datos1 = await postCorreoCliente(formData);

        btnFormEnviar.classList.remove("enviando");
        btnFormEnviar.classList.add("btn-form-guardar")
        btnFormEnviar.disabled = false;
        btnFormEnviar.textContent = "Enviar";

        verificarRespuesta(datos1);

        // y verifica si todo fue okey o hubo algún error
        const modalContactos2 = document.getElementById("modal-contactos-2")
        mostrarModalContacto2(modalContactos2, false);

        const datosFormulario = {
            idContacto: idContactoFila
        }

        console.log("Voy a enviar :", datosFormulario);

        const datos2 = await putContacto(datosFormulario);

        verificarRespuesta(datos2, false);

        console.log("Todo oka");


        await llenarTabla(tbMensajesDeContacto);

    })
}


