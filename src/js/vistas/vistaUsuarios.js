import { deleteUsuario, getUsuarios, postUsuario, putUsuario, getMenuUsuarios } from "../utils/apiServicios.js";
import { renderizarContenido } from "../componentes/renderizarContenido.js";
import { verificarRespuesta , alertaConfirmar, verificarRespuestaSistema} from "../componentes/alertas.js";

// Se debe pasar como parámetro el contenedor donde se colocará el contenido de HTML
export async function inicializarVista(contenedor) {

    console.log("Inicializando Vista: Usuarios...");

    // obtiene el bloque HTML del menú de usuarios
    const datos = await getMenuUsuarios();

    verificarRespuestaSistema(datos);
    
    const contenidoHtml = datos.respuesta;

    contenedor.removeAttribute("style");
    contenedor.classList.remove("flex");
    
    // Agrega el bloque HTML al contenedor enviado
    await renderizarContenido(contenedor, contenidoHtml);

    const btnAgregarUsuario = document.getElementById("btn-agregar-usuario");
    const txtBuscador = document.getElementById("search-usuarios");
    // console.log(txtBuscador.value);
    const tbUsuarios = document.querySelector("#tb-usuarios tbody");
    const btnCerrarModal = document.getElementById("btn-modal-cerrar");
    const btnFormGuardar = document.getElementById("btn-form-guardar");

    // Se les agrega las acciones a los botones de la vista usuario
    agregarAccionBtnAgregarUsuario(btnAgregarUsuario);
    agregarAccionBtnCerrarModal(btnCerrarModal);

    // Una vez que el bloque HTML ya está en el DOM,
    // esperamos que se cargue la tabla con los iconos de la columna "ACCIONES"
    await llenarTabla(tbUsuarios);
    agregarAccionTxtBuscador(txtBuscador, tbUsuarios);

    // Trae los botones de editar y eliminar de todas las filas de la tabla
    const listaBtnEditarUsuario = document.querySelectorAll(".btn-editar-usuario");
    const listaBtnEliminarUsuario = document.querySelectorAll(".btn-eliminar-usuario");

    agregarAccionBtnActualizarUsuario(listaBtnEditarUsuario);
    agregarAccionBtnEliminarUsuario(listaBtnEliminarUsuario, tbUsuarios);
    await agregarAccionBtnFormGuardar(btnFormGuardar, tbUsuarios);

}

/**
 * 
 * @param {HTMLTableElement} tablaUsuarios
 */
async function llenarTabla(tablaUsuarios) {

    // Obtiene la lista de usuarios de la base de datos
    const datos = await getUsuarios();

    verificarRespuesta(datos, false, false);

    // Limpia las filas de la tabla
    tablaUsuarios.innerHTML = "";

    // Recorre las filas obtenidas de la base de datos una por una
    datos.usuarios.forEach(usuarioBD => {

        // Crea una etiqueta fila
        const filaNueva = document.createElement("tr");

        // A la fila creada se le insertan los datos de las filas obtenidas de la base de datos
        filaNueva.innerHTML = `
                            <td data-id-usuario="${usuarioBD.id_cuenta}" class="td-usuario" >${usuarioBD.usuario}</td>
                            <td class="td-rol" >${usuarioBD.rol_cuenta}</td>
                            <td class="td-contraseña" >${usuarioBD.contrasenia}</td>
                            <td data-id-estado="${usuarioBD.id_estado_cuenta}" class="td-estado" >${usuarioBD.nombre_est_cuenta}</td>
                            <td class="td-accion">
                                <div class="flex" style="gap: 20px">
                                    <button class="btn-editar-usuario btn-editar">
                                        <i class="fa-solid fa-pen fa-xl"></i>
                                    </button>
                                    <button  class="btn-eliminar-usuario btn-eliminar">
                                        <i class="fa-solid fa-trash fa-xl"></i>
                                    </button>
                                </div>
                            </td>
                        `;

        // Una vez la fila creada ya tiene los datos insertados, esta es insertada en la tabla 
        // de la vista de usuarios, y así sucesivamente
        tablaUsuarios.appendChild(filaNueva);

    });

    console.log("Cantidad de filas:", tablaUsuarios.rows.length);

    // Se obtiene nuevamente los botones de las filas de la tabla, ya que esta función se va
    // a utilizar cada vez que se agrega, actualiza y elimina un usuario
    const nuevaListaBtnEditarUsuario = document.querySelectorAll(".btn-editar-usuario");
    const nuevaListaBtnEliminarUsuario = document.querySelectorAll(".btn-eliminar-usuario");

    // Se le asignan nuevamente las acciones a los botones de la columna "ACCIONES"
    agregarAccionBtnActualizarUsuario(nuevaListaBtnEditarUsuario);
    agregarAccionBtnEliminarUsuario(nuevaListaBtnEliminarUsuario, tablaUsuarios);

}

/**
 * 
 * @param {HTMLButtonElement} btnAgregarUsuario 
 */
function agregarAccionBtnAgregarUsuario(btnAgregarUsuario) {

    btnAgregarUsuario.addEventListener("click", function () {
        const tituloModal = document.getElementById("modal-titulo-usuarios");
        const txtUsuario = document.getElementById("txt-usuario");
        const txtContraseña = document.getElementById("txt-contraseña");
        const cboRol = document.getElementById("cbo-rol");

        tituloModal.innerHTML = "Agregar Usuario";
        // Limpia el formulario del modal
        txtUsuario.value = "";
        txtContraseña.value = "";
        cboRol.value = "";

        const btnFormGuardar = document.getElementById("btn-form-guardar");

        btnFormGuardar.dataset.origen = "agregar";

        mostrarCboEstado(false);

        mostrarModalUsuario();
    });

}

/**
 * 
 * @param {HTMLInputElement} txtBuscador
 * @param {HTMLTableElement} tbUsuarios
 */
function agregarAccionTxtBuscador(txtBuscador, tbUsuarios) {
    if (txtBuscador && !txtBuscador.placeholder) {
        txtBuscador.placeholder = "Buscar Usuario";
    }

    txtBuscador.addEventListener("input", function () {
        const textBuscar = txtBuscador.value.toLocaleLowerCase().trim();

        let vueltasFila = 0;
        let i = 0;
        if (textBuscar !== "") {
            console.log(textBuscar);
            tbUsuarios.querySelectorAll("tr").forEach(tbFila => {
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

            tbUsuarios.querySelectorAll("tr").forEach(tbFila => {
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

        mostrarModalUsuario(false);
    })
}

/**
 * 
 * @param {HTMLButtonElement} btnFormGuardar 
 * @param {HTMLTableElement} tablaUsuarios
 */
async function agregarAccionBtnFormGuardar(btnFormGuardar, tablaUsuarios) {

    btnFormGuardar.addEventListener("click", async (e) => {

        // Evita que al presionar el botón de Guardar del modal se recargue 
        // la pagina
        e.preventDefault();

        // Obtiene los valores de los formularios, y lo convierte en un json
        const datosFormulario = {
            usuario: document.getElementById("txt-usuario").value,
            contraseña: document.getElementById("txt-contraseña").value,
            idRol: document.getElementById("cbo-rol").value
        }

        console.log("Voy a enviar:", datosFormulario);

        // Verifica que botón se presionó, si el botón de agregar usuario o
        // el botón de editar usuario
        if (btnFormGuardar.dataset.origen === "agregar") {

            // Obtiene la respuesta si se agregó el usuario a la base de datos
            const datos = await postUsuario(datosFormulario);

            // y verifica si todo fue okey o hubo algún error
            verificarRespuesta(datos);

        } else if (btnFormGuardar.dataset.origen === "editar") {

            // agrega tambien el idUsuario y idEstado al objeto "datosFormulario"
            datosFormulario.idUsuario = document.getElementById("txt-usuario").dataset.idUsuario;
            datosFormulario.idEstado = document.getElementById("cbo-estado").value;

            const datos = await putUsuario(datosFormulario);

            verificarRespuesta(datos);

        }

        await llenarTabla(tablaUsuarios);

        mostrarModalUsuario(false);
    })
}

/**
 * 
 * @param {HTMLButtonElement[]} listaBtnEditarUsuario 
 */
function agregarAccionBtnActualizarUsuario(listaBtnEditarUsuario) {

    // Le asignará el evento a todos los botones de actualizar
    listaBtnEditarUsuario.forEach((btnEditarUsuario) => {

        btnEditarUsuario.addEventListener("click", function () {

            const tituloModal = document.getElementById("modal-titulo-usuarios");
            tituloModal.innerHTML = "Editar Usuario";

            // Obtiene la fila de donde se dió click al botón de editar
            const filaDatos = btnEditarUsuario.closest("tr").querySelectorAll("td");

            // Obtiene los valores de las celdas de la fila obtenida
            const idUsuarioFila = filaDatos[0].dataset.idUsuario;
            const usuarioFila = filaDatos[0].textContent.trim();
            const rolFila = filaDatos[1].textContent.trim();
            const contraseñaFila = filaDatos[2].textContent.trim();
            const estadoFila = filaDatos[3].textContent.trim();

            const txtUsuario = document.getElementById("txt-usuario");
            const txtContraseña = document.getElementById("txt-contraseña");
            const cboRol = document.getElementById("cbo-rol");
            const cboEstado = document.getElementById("cbo-estado");

            // Busca la opcion del combo box de rol que coincida con el nombre del rol del usuario a actualizar,
            // para que cuando se abra el modal de editar usuario, aparezca el rol en el combo box ya seleccionado
            for (const opcion of cboRol.options) {
                if (opcion.textContent.trim().toLowerCase() === rolFila.trim().toLowerCase()) {
                    cboRol.value = opcion.value;
                    break;
                }
            }

            // Lo mismo que arriba, pero ahora para el estado
            for (const opcion of cboEstado.options) {
                if (opcion.textContent.trim().toLowerCase() === estadoFila.trim().toLowerCase()) {
                    cboEstado.value = opcion.value;
                    break;
                }
            }

            const btnFormGuardar = document.getElementById("btn-form-guardar");

            // Se le asignan los valores de la fila al modal editar usuario
            txtUsuario.value = usuarioFila;
            txtContraseña.value = contraseñaFila;
            txtUsuario.dataset.idUsuario = idUsuarioFila;

            // Se le asigna la accion que se va a realizar
            btnFormGuardar.dataset.origen = "editar";

            mostrarCboEstado();
            mostrarModalUsuario();
        })
    });
}

/**
 * 
 * @param {HTMLButtonElement[]} listaBtnEliminarUsuario 
 * @param {HTMLTableElement} tbUsuarios 
 */
function agregarAccionBtnEliminarUsuario(listaBtnEliminarUsuario, tbUsuarios) {

    listaBtnEliminarUsuario.forEach((btnEliminarUsuario) => {
        btnEliminarUsuario.addEventListener("click", async function () {

            const result = await alertaConfirmar();

            if (result.isConfirmed) {

                const datosFormulario = {
                    idUsuario: btnEliminarUsuario.closest("tr").querySelectorAll("td")[0].dataset.idUsuario
                }

                const datos = await deleteUsuario(datosFormulario);

                verificarRespuesta(datos);

                if (datos.estado === "ok") {

                    await llenarTabla(tbUsuarios);

                }


            }
        })
    });
}

// Si la funcion se llama sin definir un parámetro, por defecto el parámetro "accion"
// es true
function mostrarModalUsuario(accion = true) {

    const modalUsuarios = document.getElementById("modal-usuarios");

    // Si es true, muestra el modal
    if (accion) {
        modalUsuarios.classList.remove("oculto");
    } else {
        // Si es falso, oculta el modal
        modalUsuarios.classList.add("oculto");

        const tituloModal = document.getElementById("modal-titulo-usuarios");
        const txtUsuario = document.getElementById("txt-usuario");
        const btnFormGuardar = document.getElementById("btn-form-guardar");

        // y limpia el titulo del formulario
        tituloModal.innerHTML = "";
        txtUsuario.removeAttribute("data-id-usuario");
        btnFormGuardar.removeAttribute("data-origen");
    }
}

// Lo mismo que la funcion mostrarModalUsuario, pero ahora para el combo box
// de Estado
function mostrarCboEstado(accion = true) {
    const cboEstado = document.getElementById("cont-cbo-estado");

    if (accion) {
        cboEstado.classList.remove("oculto");
    } else {
        cboEstado.classList.add("oculto");
    }
}
