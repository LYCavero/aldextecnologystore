import rutas from "./rutas.js";

export function getMenusUsuarioLogueado() {
    return callApi("obtener_menus_bd.php");
}

/** Apis Menu Usuarios **/

export function getUsuarios(){
    return callApi("obtener_usuarios_bd.php");
}

export function postUsuario(datosFormulario) {
    return callApi("agregar_usuario_bd.php", "POST", datosFormulario);
}

export function putUsuario(datosFormulario) {
    return callApi("actualizar_usuario_bd.php", "POST", datosFormulario);
}

export function deleteUsuario(datosFormulario) {
    return callApi("eliminar_usuario_bd.php", "POST", datosFormulario);
}

/** Apis Menu Productos **/

export function getProductos(){
    return callApi("obtener_productos_bd_js.php");
}

export function postProducto(datosFormulario) {
    return callApi("agregar_producto_bd.php", "POST", datosFormulario,"formData");
}

export function putProducto(datosFormulario) {
    return callApi("actualizar_producto_bd.php", "POST", datosFormulario,"formData");
}

export function deleteProducto(datosFormulario) {
    return callApi("eliminar_producto_bd.php", "POST", datosFormulario);
}

/** Apis Mensajes de Contactos **/

export function getMensajesDeContactos(){
    return callApi("obtener_contactos_bd.php");
}

export function postCorreoCliente(datosFormularioCorreo){
    return callApi("enviar_correo_soporte_copy.php","POST",datosFormularioCorreo,"formData");
}

export function putContacto(datosFormulario){
    return callApi("actualizar_estado_respondido_contacto_bd.php", "POST", datosFormulario);
}

export function putEstadoContacto(datosFormulario){
    return callApi("actualizar_estado_leido_contacto_bd.php", "POST", datosFormulario);
}

export function getProductosCarrito(){
    return callApi("obtener_productos_carrito_bd_js.php");
}


/** Menus **/

export function getArchivosVista(){
    return callApi("obtener_archivos_vista.php");
}

export function getMenuUsuarios(){
    return getFragmentoContenido("usuarios");
}

export function getMenuProductos(){
    return getFragmentoContenido("productos");
}

export function getMenuMensajesDeContacto(){
    return getFragmentoContenido("mensajes-de-contacto");
}


export async function getFragmentoContenido(menu) {
    
    const respuesta = await fetch(rutas.API + `controlador_fragmentos_contenido.php?menu=${menu}`);

    return respuesta.json();
}

async function callApi(archivo, metodo = "GET", datos = null, header = "json") {

    const opciones = {
        method: metodo,
        headers: {}
    }

    if(header === "json" && metodo === "POST" && datos){
        opciones.headers["Content-Type"] = "application/json";
        opciones.body = JSON.stringify(datos);
    } else if (header === "formData" && metodo === "POST" && datos){
        opciones.body = datos;
    }

    const respuesta = await fetch(rutas.API + `controlador_api_bd.php?archivo=${archivo}`,opciones);

    return await respuesta.json();

}