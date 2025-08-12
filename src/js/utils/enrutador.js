import { alertaInfo, verificarRespuesta, verificarRespuestaSistema } from "../componentes/alertas.js";
import rutas from "../utils/rutas.js";
import { getArchivosVista, getMenusUsuarioLogueado } from "./apiServicios.js";

/**
 * 
 * @param {Array} menus 
 * @returns 
 */
export async function enrutador() {

    const divContenido = document.getElementById("contenido");
    divContenido.innerHTML = "";
    divContenido.classList.add("flex");

    const hash = location.hash; // obtiene desde el # hasta adelante
    console.log("El hash es : " + hash);

    // debugger;
    if (!hash || hash === "#/") {

        const datos = await getMenusUsuarioLogueado();
        verificarRespuestaSistema(datos);

        const menus = datos.respuesta.menus;

        if (menus.length > 0) {

            console.log("Se empieza a cargar el menú principal...");

            let menuPrincipal = null;

            for (const menu of menus) {

                if (menu.id_mod_principal == 1) {
                    menuPrincipal = menu;
                    break;
                }
            }


            // console.log(menus[0].slug);

            // const menuPorDefecto = menus[0].slug; // Usa el primer menú como predeterminado
            if (menuPrincipal != null) {
                // debugger;
                // const { inicializarVista } = await modulosVistas[menuPorDefecto]();
                location.hash = `#/${menuPrincipal.slug}`;
                // const { inicializarVista } = await import(rutas.VISTAS + "vista"+ menuPrincipal.titulo_mod + ".js");
                // await inicializarVista(divContenido);
                return;

            } // else, signfica que en la bd, no existe la relacion del modulo principal con el rol

            console.log("Se terminó de cargar el menú principal");

        } else {
            console.log("Ta raro");
        }
    }



    const slug = hash.startsWith("#/") ? hash.slice(2) : ""; // elimina "#/" y obtiene solo el slug

    if (!(slug.length === 0)) {
        console.log("El slug detectado es: " + slug);

        // divContenido.innerHTML = `<i class="fa-solid fa-spinner fa-10x fa-spin-pulse" style="color: #2c13aa;"></i>`; // opcional: feedback

        // console.log("Salió el slug " + slug);

        const $loader = document.createElement("img");
        $loader.src = "../img/svg/loader.svg";

        divContenido.style.justifyContent = "center";
        divContenido.style.alignItems = "center";
        divContenido.appendChild($loader);

        const nombreArchivoVista = "vista" + convertirAPascalCase(slug) + ".js";
        console.log(nombreArchivoVista);

        const datos = await getArchivosVista();       
        const archivosVista = datos.respuesta;
        // debugger;
        if(!(archivosVista.includes(nombreArchivoVista))){
            alertaInfo("Sección en desarrollo", "Estamos trabajando para habilitar esta sección");
            divContenido.innerHTML = "";
            return;
        }



        const { inicializarVista } = await import(rutas.VISTAS + nombreArchivoVista);
        await inicializarVista(divContenido);

        return divContenido;

    } else {
        console.log("No hay slug");
    }


    //     } else {
    //         console.log("El hash está vacío");
    //     }
}


function convertirAPascalCase(cadena) {
    if (cadena.includes("-")) {
        return cadena
            .split("-")
            .map(palabra => palabra.charAt(0).toUpperCase() + palabra.slice(1))
            .join("");
    } else {
        return cadena.charAt(0).toUpperCase() + cadena.slice(1);
    }
}