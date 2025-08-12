/**
 * 
 * @param {Object} datos 
 */

// Obtiene el objeto y hace la verificacion
export function verificarRespuesta(datos, mostrarOk = true, mostrarError = true) {
    // console.log(datos);
    
    // Si salió todo bien va a mostrar una alerta con el mensaje
    if (datos.estado === "ok") {

        // como por defecto está en true, normalmente mostrará la alerta
        // pero en caso que no se quiera mostrar (como al obtener los datos de la tabla), 
        // simplemente se envía false
        if (mostrarOk) {
            alertaExitoso(datos.respuesta);
        }
    } else {
        // De igual manera, si no se quiere mostrar la alerta de error, se debe 
        // ingresar false al parámetro "mostrarError"
        if (mostrarError) {

            alertaError(datos.respuesta);
        }
    }
}

/**
 * 
 * @param {Object} datos 
 */

// Obtiene el objeto y hace la verificacion
export function verificarRespuestaSistema(datos) {

    if (datos.estado === "error" || datos.estado === "warning") {

        if (datos.codigo != null) {
            alertaSistema(true,datos.respuesta);
            
        } else {
            alertaSistema();
            // return;
        }
        document.getElementById("contenido").innerHTML = "";
        throw new Error(datos.respuesta);
    }
}

export function alertaExitoso(mensaje) {
    Swal.fire({
        title: mensaje,
        icon: "success",
        draggable: true
    });
}

export function alertaError(mensaje) {
    Swal.fire({
        title: mensaje,
        icon: "error",
        draggable: true
    });
}

export function alertaSistema(mensajePermitido = false, mensaje = "") {

    if (mensajePermitido) {
        Swal.fire({
            title: "Error",
            text: mensaje,
            icon: "error"
        });
    } else {

        Swal.fire({
            title: "En mantenimiento",
            text: "Comuniquese con el administrador a cargo",
            icon: "info"
        });
    }
}

export function alertaInfo(titulo="",mensaje=""){
    Swal.fire({
        title: titulo.trim()===""?"Informacion":titulo,
        text: mensaje.trim()===""?"Información mostrada":mensaje,
        icon: "info",
        draggable: true
    });
}

export async function alertaConfirmar() {

    return await Swal.fire({
        title: "Estás seguro?",
        text: "No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    })

    // .then((result) => {
    //     if (result.isConfirmed) {
    //         Swal.fire({
    //             title: mensaje,
    //             icon: "success"
    //         });
    //     }
    // });
}