function mensajeValidacion() {

}

function mensajeLogueado() {
    Swal.fire({
        title: "Inicio de sesion exitoso",
        icon: "success",
        draggable: true
    });
}

function mensajeNoLogueado() {
    Swal.fire({
        title: "Incorrecto",
        text: "Usuario o contraseña incorrectos",
        icon: "error",
    });
}