import { alertaError, alertaSistema, verificarRespuesta, verificarRespuestaSistema } from "./componentes/alertas.js";
import { inicializarSeccionesCabecera } from "./componentes/Cabecera.js";
import { getMenusUsuarioLogueado } from "./utils/apiServicios.js";
import { enrutador } from "./utils/enrutador.js";
import rutas from "./utils/rutas.js";

export async function App() {

    
    const $loader = document.createElement("img");
    $loader.src = "../img/svg/loader.svg";

    const divContenido = document.getElementById("contenido");
    divContenido.innerHTML = "";
    divContenido.classList.add("flex");
    divContenido.style.justifyContent = "center";
    divContenido.style.alignItems = "center";
    divContenido.appendChild($loader);

    const spanRol = document.getElementById("rol-cuenta");

    // try {

        const datos = await getMenusUsuarioLogueado();
        console.log(datos);
        verificarRespuestaSistema(datos);
        
        const menus = datos.respuesta.menus;
        const rolUsuario = datos.respuesta.rolUsuario;
        
        
        if(document.querySelectorAll("#secciones a").length === 0){
            console.log("Muestra las secciones de cabecera...");
            inicializarSeccionesCabecera(menus);
        }

        const btnLogout = document.getElementById("btn-logout");
        
        btnLogout.addEventListener("click", function(){
            window.location.href = rutas.SISTEMA+"logoutControlador.php";
        })
        
        // const esperar = ms => new Promise(r => setTimeout(r, ms));

        // await esperar(5000);

        spanRol.textContent = rolUsuario || 'Usuario';

        window.addEventListener("hashchange",enrutador);

        await enrutador();


    // } catch (error) {
    //     console.error("Excepcion capturada: ",error);
    //     // divContenido.innerHTML = '<p class="error-message">Servicio en mantenimiento. Por favor, contacte con el administrador.</p>';
    // }

}