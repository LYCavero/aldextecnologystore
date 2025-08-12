
/**
 * 
 * @param {HTMLDivElement} contenedor 
 * @param {*} htmlContenido 
 */
export async function renderizarContenido(contenedor, htmlContenido) 
{
    contenedor.innerHTML = htmlContenido;
}