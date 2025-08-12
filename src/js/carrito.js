import rutas from "./utils/rutas.js";


document.addEventListener("DOMContentLoaded", function(){

    const carBtnRegistrarCliente = document.getElementById("car-btn-registrar-cliente");
    const carBtnVerProductos = document.getElementById("car-btn-ver-productos");
    
    document.addEventListener("click",async function(e){
        if (e.target == carBtnRegistrarCliente) {
            window.location.href = rutas.SISTEMA + "login.php";
        }
        
        if(e.target == carBtnVerProductos){
            window.location.href = "productos.php";
        }
    
        if(e.target.closest(".btn-aumentar")){
            const botonAumentar = e.target.closest(".btn-aumentar");
            const cantidadSpan = botonAumentar.parentElement.querySelector(".cantidad");
            const idProducto = botonAumentar.parentElement.querySelector(".btn-aumentar").dataset.id;
            cantidadSpan.textContent = parseInt(cantidadSpan.textContent) + 1;

            const datos = {
                idProducto : idProducto,
                cantidadProducto: cantidadSpan.textContent
            }

            // console.log(datos);
            

            fetch("./api/actualizar_cantidad_producto_carrito_bd.php",{
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            });

            recalcularPrecioTotalCarrito();
        }
    
        if(e.target.closest(".btn-disminuir")){
            const botonDisminuir = e.target.closest(".btn-disminuir");
            const cantidadSpan = botonDisminuir.parentElement.querySelector(".cantidad");
            const idProducto = botonDisminuir.parentElement.querySelector(".btn-disminuir").dataset.id;

            let cantidad = parseInt(cantidadSpan.textContent);
            if (cantidad > 1) {
                cantidadSpan.textContent = cantidad - 1;

                const datos = {
                    idProducto : idProducto,
                    cantidadProducto: cantidadSpan.textContent
                }

                // console.log(datos);
                

                fetch("./api/actualizar_cantidad_producto_carrito_bd.php",{
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(datos)
                });

                recalcularPrecioTotalCarrito();
            }
        }

        if(e.target.closest(".btn-eliminar-producto")){
            const botonElminarProducto = e.target.closest(".btn-eliminar-producto");
            const idProducto = botonElminarProducto.parentElement.querySelector(".btn-eliminar-producto").dataset.id;

            const datos = {
                idProducto : idProducto
            }

            fetch("./api/eliminar_producto_carrito_bd.php",{    
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            })
            .then(
                function(){
                    window.location.href = "carrito.php";
                }
            );

            // recalcularPrecioTotalCarrito();
            
        }

        if(e.target.closest(".btn-finalizar-compra")){

            await fetch("./api/generar_venta_cerrar_carrito_bd.php",{    
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(result => result.json())
            .then(json => console.log(json));

            // window.location.href = "carrito.php";
            // recalcularPrecioTotalCarrito();
            
        }

        

    })
    
    function recalcularPrecioTotalCarrito() {

        let productosCarrito = document.querySelectorAll(".carrito-item");
        let total = 0;

        if(productosCarrito.length === 0){
            const totalElemento = document.getElementById("total-carrito");
            if (totalElemento) {
                totalElemento.textContent = `S/ 0.00`;
            }
            return;
        }

        productosCarrito.forEach(item => {
            const cantidad = parseInt(item.querySelector(".cantidad").textContent);
            const precioTexto = item.querySelector(".carrito-item-detalles p:last-child").textContent;
            const precio = parseFloat(precioTexto.replace("Precio: S/", "").trim().replace(",", ""));
            console.log(precio);
            
            total += cantidad * precio;
        });
    
        document.getElementById("total-carrito").textContent = `S/ ${total.toFixed(2)}`;
    }
    
    recalcularPrecioTotalCarrito();
});



