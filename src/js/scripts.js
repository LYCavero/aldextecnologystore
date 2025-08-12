document.addEventListener('DOMContentLoaded', () => {

    const productModal = document.getElementById('productModal');
    const btnAgregarProducto = document.getElementById('btnAgregarProducto');
    const closeButton = productModal.querySelector('.close-button'); // Botón de cerrar (la 'x')
    const btnCerrar = productModal.querySelector('.btn-cerrar'); // Botón 'CERRAR' en el footer del modal
    const modalTitle = document.getElementById('modalTitle'); // Título del modal
    const productForm = document.getElementById('productForm'); // El formulario completo
    const editButtons = document.querySelectorAll('.edit-btn'); // Selecciona todos los botones de edición

    // --- Evento para abrir el modal con el botón "Agregar producto" ---
    btnAgregarProducto.addEventListener('click', () => {
        // Mostrar el modal
        productModal.style.display = 'flex'; // Usamos 'flex' para que se centre automáticamente

        // Limpiar el formulario para un nuevo producto
        productForm.reset();
        modalTitle.textContent = 'Agregar producto';
    });


    // Cerrar al hacer clic en la 'x'
    closeButton.addEventListener('click', () => {
        productModal.style.display = 'none';
    });

    // Cerrar al hacer clic en el botón 'CERRAR'
    btnCerrar.addEventListener('click', () => {
        productModal.style.display = 'none';
    });

    // Cerrar al hacer clic fuera del contenido del modal
    window.addEventListener('click', (event) => {
        if (event.target == productModal) {
            productModal.style.display = 'none';
        }
    });

    // ---para abrir el modal con los botones "Editar" de la tabla ---
    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const row = button.closest('tr');
            const idProducto = row.dataset.id;
            const descripcion = row.dataset.descripcion;
            const precio = row.dataset.precio;

            // Rellenar el formulario del modal con los datos del producto
            document.getElementById('codigo').value = idProducto;
            document.getElementById('descripcion').value = descripcion;
            document.getElementById('precio').value = precio;

            modalTitle.textContent = 'Editar producto';


            productModal.style.display = 'flex';
        });
    });


    // --- Manejo del envío del formulario (ejemplo) ---
    productForm.addEventListener('submit', (event) => {

        const descripcion = document.getElementById('descripcion').value;
        const precio = document.getElementById('precio').value;
        const catProducto = document.getElementById('cat_producto').value;
        const imagenInput = document.getElementById('imagen');
        const imagenArchivo = imagenInput.files[0]; // archivo seleccionado (si hay)
        const codigo = document.getElementById("codigo").value;

        if (codigo !== null && codigo.trim() !== "") {
            document.getElementById('accion').value = "editar";


        } else {
            document.getElementById('accion').value = "insertar";
        }


        console.log({
            descripcion,
            precio,
            catProducto,
            imagenArchivo
        });
    });



});
