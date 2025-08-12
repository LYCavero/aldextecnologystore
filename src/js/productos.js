import { verificarRespuesta } from "./componentes/alertas.js";
import { getProductos, getProductosCarrito } from "./utils/apiServicios.js";

document.addEventListener("DOMContentLoaded",function(){

    const contenedorProductoOriginal = document.querySelector(".cart-producto");
    const contenedorGrid = document.getElementById("contenedor-grid");
    const contenedorContenido = document.getElementById("contenedor-contenido");
    const contenedorOpcionesCarrito = document.getElementById("contenedor-opciones-carrito");
    let productoSeleccionado = null;
    let listaProductosCarrito = [];
    let logueado = false;
     contenedorProductoOriginal.remove();

    document.addEventListener("click",async function(e){

        if(e.target.closest(".contenedor-opciones-carrito")){

            if(logueado){
                if(contenedorOpcionesCarrito.textContent == "Agregar al carrito"){
    
                    const datos = {
                        idProducto: contenedorOpcionesCarrito.dataset.idProducto,
                        precioUnitario: contenedorOpcionesCarrito.dataset.precioUnitario
                    }
        
                    fetch("./api/agregar_producto_carrito_bd.php",{    
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(datos)
                    });
    
                    await obtenerProductosCarrito();
                    // await obtenerProductos();
                    // console.log(listaProductosCarrito);
                } else if (contenedorOpcionesCarrito.textContent == "Quitar del carrito"){
                    const datos = {
                        idProducto: contenedorOpcionesCarrito.dataset.idProducto
                    }
    
                    fetch("./api/eliminar_producto_carrito_bd.php",{    
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(datos)
                    });
    
                    await obtenerProductosCarrito();
                }
                contenedorOpcionesCarrito.style.display = "none";
            } else {
                window.location.href = "./sistema/login.php";
            }
        }




    })
    
    async function obtenerProductos() {
        // try {
    
            await obtenerProductosCarrito();
            console.log("Se muestran los datos de la listaProductosCarrito: ", listaProductosCarrito);
    
            const datos = await getProductos();
            verificarRespuesta(datos, false);
    
            const listaProductosBd = datos.respuesta;
    
            listaProductosBd.forEach(producto => {
                // Se remueve el contenedor de modelo luego de que se crean todos los productos con contenedorProductoOriginal.remove();
                let contenedorProductoClon = clonarDiv(contenedorProductoOriginal);
    
                contenedorProductoClon.dataset.idProducto = producto.id_producto;
                contenedorProductoClon.querySelector(".cart-producto-img").setAttribute("src", producto.imagen.slice(3));
                contenedorProductoClon.querySelector(".cart-producto-descrip").setAttribute("title", producto.descripcion);
                contenedorProductoClon.querySelector(".cart-producto-descrip").textContent = producto.descripcion;
                contenedorProductoClon.querySelector(".cart-producto-precio").textContent = producto.precio;
                contenedorProductoClon.querySelector(".cart-producto-stock").textContent = "(" + producto.stock + ")";
    
                contenedorGrid.appendChild(contenedorProductoClon);
            });
    
            agregarAccionContenedorContenido(listaProductosBd);
            // agregarAccionContenedorOpciones();

    }
    
    async function obtenerProductosCarrito() {
        const datos = await getProductosCarrito();
    
        verificarRespuesta(datos,false);
        
        listaProductosCarrito = datos.respuesta;
        logueado = datos.logueado;
        console.log(listaProductosCarrito);
        
    }
    
    function clonarDiv(div) {
        /* (true) se clona el div con los divs hijos y su contenido*/
        return div.cloneNode(true);
    }
    
    /**
     * 
     * @param {Array} listaProductosBd 
     */
    function agregarAccionContenedorContenido(listaProductosBd) {
    
        contenedorContenido.addEventListener("click", function (evento) {
    
            const cartProducto = evento.target.closest(".cart-producto");
    
            // Validamos que el clic se haya hecho dentro de un div .cart-producto
            if (cartProducto && contenedorContenido.contains(cartProducto)) {

                console.log("ID PRODUCTO:", cartProducto.dataset.idProducto);
                const rect = cartProducto.getBoundingClientRect();

                contenedorOpcionesCarrito.textContent = mostrarMensajeOpcion( cartProducto.dataset.idProducto);
                contenedorOpcionesCarrito.style.top = (rect.top + window.scrollY) + "px";
                contenedorOpcionesCarrito.style.left = (rect.right + window.scrollX) + "px";
                contenedorOpcionesCarrito.style.display = "block";

                contenedorOpcionesCarrito.dataset.idProducto = cartProducto.dataset.idProducto;
                const precioTexto = cartProducto.querySelector(".cart-producto-precio").textContent;
                contenedorOpcionesCarrito.dataset.precioUnitario = parseFloat(precioTexto.trim());
    
                productoSeleccionado = cartProducto;
    
            }
    
            if (!evento.target.closest(".cart-producto") && !evento.target.closest(".contenedor-opciones-carrito")) {
                contenedorOpcionesCarrito.style.display = "none";
            }
    
        })
    
    }
    
    function mostrarMensajeOpcion(idProducto) {
        
        let productoEncontrado = listaProductosCarrito.filter(producto => producto.id_producto == idProducto);
    
        if(productoEncontrado.length == 0){
            return "Agregar al carrito";
        } else {
            return "Quitar del carrito";
        }
    
    }
    
    // function agregarAccionContenedorOpciones(){
    //     contenedorOpcionesCarrito.addEventListener("click", (evento) => {
    
    //     const divContenedorOpcionesCarrito = evento.target.closest(".contenedor-opciones-carrito");
    
    //     if(divContenedorOpcionesCarrito && contenedorOpcionesCarrito.contains(divContenedorOpcionesCarrito)){
    
    //         let idProductoSeleccionado = productoSeleccionado.dataset.idProducto;
    //         let indexProductoCarrito = listaProductosCarrito.findIndex(producto => producto.id_producto == idProductoSeleccionado);
            
        
    //         // let productoEncontrado = listaProductosCarrito.filter(producto => producto.id == idProductoSeleccionado);
    //         // console.log(productoEncontrado);
    //         // if(productoEncontrado == 0){
    //         if(indexProductoCarrito == -1){
                
    //             agregarProductoCarrito(
    //                 productoSeleccionado,
    //                 listaProductosCarrito);
    //         } else {

    //             listaProductosCarrito.splice(indexProductoCarrito,1);
    //             console.log(listaProductosCarrito);
    //         }
    
    //     }
    
    // })
    // }
    
    // /**
    //  * 
    //  * @param {HTMLDivElement} productoSeleccionado 
    //  * @param {Array} listaProductosCarrito 
    //  */
    // async function agregarProductoCarrito(productoSeleccionado, listaProductosCarrito) {
    //     let productoJson = {
    //         id: contenedorProducto.querySelector(".producto-id").textContent,
    //         title: contenedorProducto.querySelector(".producto-titulo").textContent,
    //         title: contenedorProducto.querySelector(".producto-titulo").textContent,
    //         price: contenedorProducto.querySelector(".producto-precio").textContent,
    //         description: contenedorProducto.querySelector(".producto-descripcion").textContent,
    //         category: contenedorProducto.querySelector(".producto-categoria").textContent,
    //         image: contenedorProducto.querySelector(".producto-imagen").textContent,
    //         calificacion: contenedorProducto.querySelector(".producto-calificacion").textContent,
    //         stock: contenedorProducto.querySelector(".producto-stock").textContent
    //     }
        
    //     listaProductosCarrito.push(productoJson);
    //     console.log(listaProductosCarrito);
    // }
    
    obtenerProductos();

});