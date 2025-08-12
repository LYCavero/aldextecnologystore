    <?php
    session_start();
    include("../cls_conectar/cls_conexion.php");
    $obj = new Conexion();
    $conexion = $obj->getConectar();

    //leer los datos del formulario login.php
    $nombPro = $_POST["descripcion"];
    $prePro = $_POST["precio"];
    $idCatPro = $_POST["catProducto"];



    // Validar y leer imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $imgPro = file_get_contents($_FILES['imagen']['tmp_name']);

        $accion = $_POST['accion'];

        if ($accion === 'insertar') {
            //query para insertar
            $sql = "insert into producto(nombre,precio,id_categoria_producto,stock,imagen)
                values(?,?,?,0,?);";

            $stmt = $conexion->prepare($sql);

            $stmt->bind_param("sdib", $nombPro, $prePro, $idCatPro, $null);
        } else {

            $idProducto = $_POST["codigo"];

            //query para editar
            $sql = "
                    update producto
                    set nombre = ?,
                        precio = ?,
                        id_categoria_producto = ?,
                        imagen = ?
                    where id_producto = ?;
                    ";

            $stmt = $conexion->prepare($sql);

            $stmt->bind_param("sdibi", $nombPro, $prePro, $idCatPro, $null, $idProducto);
        }



        $null = null;
        $stmt->send_long_data(3, $imgPro);


        if ($stmt->execute()) {
            echo "Se realizó la operacion correctamente";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "No se subió ninguna imagen.";
    }
    $conexion->close();

    header("Location: administrador-productos.php");
