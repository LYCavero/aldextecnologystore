<?php 
  //recuperar sesión
  session_start();
    //incluir página cls_conexion.php con el objeto de acceder a la conexión de base de datos
	include("../cls_conectar/cls_conexion.php");
	//crear objeto de la clase Conexion
	$obj=new Conexion();
	//sentencia sql
	$sql="select *from tb_medicamento";
	$rs=mysqli_query($obj->getConectar(),$sql);	

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicamento</title>

    <link rel="stylesheet" href="css/miEstilo.css">
</head>
<body>
    <div class="contenedor">
        <div class="menu">
            <div class="menuIzquierda">
               <span class="title">NavBar</span> 
            </div>
            <div class="menuDerecha">
                <div class="menus">
                        <a href="../medicamento/">Medicamento</a>     
                        <a href="#">Laboratorio</a>     
                        <a href="../medico/">Medico</a>     
                        <a href="#">Pacientes</a>     
						<a href="#">Cita</a>        
                </div>
            </div>
        </div>
        <div class="contenido">
            <h2 class="centrar">Listado de Medicamentos</h2>
            <div class="tabla">
                <div class="tablaLeft">
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
			 <?php
				while($row=mysqli_fetch_array($rs)){
			  ?>
                            <tr>
                                <td><?php echo $row[0];?></td>
                                <td><?php echo $row[1];?></td>
                                <td><?php echo $row[3];?></td>
                                <td><?php echo $row[4];?></td>
                                <td><?php echo $row[5];?></td>
                            </tr>
			  <?php
			    }
			  ?>	
                        </tbody>
                    </table>
                </div>
                <div class="tablaRight">
                    <div class="rounded1">
						  <form method="post" action="medicamentoController.php">
							  <label class="etiqueta">Código</label></br>
							  <input type="text" class="form-control" name="codigo">
							  <label class="etiqueta">Nombre</label></br>
							  <input type="text" class="form-control" placeholder="Ingresar nombre" name="nombre">
							  <label class="etiqueta">Descripción</label></br>
							  <textarea class="form-control" placeholder="Ingresar descripción" name="descripcion"  style="height: 100px"></textarea></br>
							  <label class="etiqueta">Precio</label></br>
							  <input type="text" class="form-control" placeholder="Ingresar precio" name="precio">
							  <label class="etiqueta">Stock</label></br>
							  <input type="text" class="form-control" name="stock">
							  <label class="etiqueta">Fecha Vencimiento</label></br>
							  <input type="date" class="form-control" name="fecha">
							<div class="d-grid">
							  <button type="submit" class="registrar">GRABAR</button>
							</div>
							
						  </form>

						</div>
                </div>
            </div>    
        </div>  
      
 
    </div>   
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="js/validar.js"></script> 
	

 <?php
  //validar variable de tipo sesión  "estado" y verificar su existencia
  if(isset($_SESSION["estado"]) && $_SESSION["estado"]==1 ){
     echo "<script>
            Swal.fire({
                title: 'Sistema',
                text: 'Medicamento registrado',
                icon: 'question'
                });
		   </script>";
  }	

  //eliminar variables de tipo sesión
  session_unset();
  //eliminar sesión actual
  session_destroy();
?>
</body>
</html>