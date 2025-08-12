/**
 * 
 * @param {Array} modulos 
 */
export function inicializarSeccionesCabecera(modulos) {

    const navSecciones = document.getElementById("secciones");

    let listaMenusTexto = [];

    modulos.forEach(modulo => {
        listaMenusTexto.push(modulo.slug);

        const aSeccion = document.createElement("a");
        aSeccion.href = `#/${modulo.slug}`;
        aSeccion.innerHTML = modulo.titulo_mod;

        navSecciones.appendChild(aSeccion);

    });

    console.log("Mostrar secciones de cabecera: ");
    for (const menu of listaMenusTexto) {
        console.log("- " + menu);
    }

}

