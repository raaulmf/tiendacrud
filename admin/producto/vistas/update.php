<?php

require_once $_SERVER['DOCUMENT_ROOT']."/tienda/admin/producto/dirs.php";
require_once CONTROLLER_PATH."ControladorProducto.php";
require_once CONTROLLER_PATH."ControladorImagen.php";
require_once UTILITY_PATH."funciones.php";


$nombre = $tipo = $marca = $precio = $unidades = $imagen = "";
$nombreErr = $tipoErr = $marcaErr = $precioErr = $unidadesErr = $imagenErr = "";
$imagenAnterior = "";

if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];

    $nombreVal = filtrado(($_POST["nombre"]));
    if(empty($nombreVal)){
        $nombreErr = "Por favor introduzca un nombre";
    } else{
        $nombre = $nombreVal;
    }
    

    if(isset($_POST["tipo"])){
        $tipo = filtrado($_POST["tipo"]);
    }else{
        $tipoErr = "Debe elegir al menos una opcion";
    }
   
    $marcaVal = filtrado(($_POST["marca"]));
    if(empty($marcaVal)){
        $marcaErr = "Por favor introduzca una marca";
    } else{
        $marca = $marcaVal;
    }
    if(isset($_POST["precio"])){
        $precio = filtrado($_POST["precio"]);
    }
    //$precio = filtrado($_POST["precio"]);

    //$unidades = filtrado($_POST["unidades"]);
    if(isset($_POST["unidades"])){
        $unidades = filtrado($_POST["unidades"]);
    }
    
    
    if($_FILES['imagen']['size']>0 && count($errores)==0){
        $propiedades = explode("/", $_FILES['imagen']['type']);
        $extension = $propiedades[1];
        $tam_max = 1000000; // 1MB
        $tam = $_FILES['imagen']['size'];
        $mod = true;
        // Si no coicide la extensión
        //if($extension != "jpg" && $extension != "jpeg"){
          //  $mod = false;
            //$imagenErr= "Formato debe ser jpg/jpeg";
       // }
        // si no tiene el tamaño
        if($tam>$tam_max){
            $mod = false;
            $imagenErr= "Tamaño superior al limite de: ". ($tam_max/1000). " KBytes";
        }

        // Si todo es correcto, mod = true
        if($mod){
            // salvamos la imagen
            $imagen = md5($_FILES['imagen']['tmp_name'] . $_FILES['imagen']['name'].time()) . "." . $extension;
            $controlador = ControladorImagen::getControlador();
            if(!$controlador->salvarImagen($imagen)){
                $imagenErr= "Error al procesar la imagen y subirla al servidor";
            }

            // Borramos la antigua
            $imagenAnterior = trim($_POST["imagenAnterior"]);
            if($imagenAnterior!=$imagen){
                if(!$controlador->eliminarImagen($imagenAnterior)){
                    $imagenErr= "Error al borrar la antigua imagen en el servidor";
                }
            }
        }else{
        // Si no la hemos modificado
            $imagen=trim($_POST["imagenAnterior"]);
        }

    }else{
        $imagen=trim($_POST["imagenAnterior"]);
    }

    if(empty($nombreErr) && empty($tipoErr) && empty($marcaErr) && empty($precioErr) && 
        empty($unidadesErr) && empty($imagenErr)){
       // creamos el controlador de Producto
        $controlador = ControladorProducto::getControlador();
        $estado = $controlador->actualizarProducto($id, $nombre, $tipo, $marca, $precio, $unidades, $imagen);
        if($estado){
            // El registro se ha lamacenado corectamente
            header("location: ../index.php");
            exit();
        }else{
            header("location: /tienda/admin/vistas/error.php");
           // echo "Error0";
            exit();
        }
    }else{
        alerta("Hay errores al procesar el formulario revise los errores");
    }


}

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id =  decode($_GET["id"]);
    $controlador = ControladorProducto::getControlador();
    $producto = $controlador->buscarProducto($id);
    if (!is_null($producto)) {
        $nombre = $producto->getNombre();
        $tipo = $producto->getTipo();
        $marca = $producto->getMarca();
        $precio = $producto->getPrecio();
        $unidades = $producto->getUnidades();
        $imagen = $producto->getImagen();
        $imagenAnterior = $imagen;
    }else{
    // hay un error
        header("location: /tienda/admin/vistas/error.php");
       // echo "Error1";
        exit();
    }
}else{
     // hay un error
        header("location: /tienda/admin/vistas/error.php");
       // echo "Error2";
        exit();
}


?>

<?php require_once "cabecera.php"; ?>



<h2>Modificar Producto</h2>
<p>Por favor rellene los datos para introducir un nuevo producto</p>
<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
    <fieldset><legend><b>Producto</b></legend>
        <?php echo (!empty($nombreErr)) ? 'error: ' : ''; ?>
        <label>Nombre: </label>
        <input type="text" required name="nombre" value="<?php echo $nombre; ?>"><hr>

        <?php echo (!empty($tipoErr)) ? 'error: ' : ''; ?>
        <label>Tipo: </label><br>
        <input type="radio" name="tipo" value="0-3" <?php echo (strstr($tipo, '0-3')) ? 'checked' : ''; ?>>0-3 Años</input></br>
        <input type="radio" name="tipo" value="3-6" <?php echo (strstr($tipo, '3-6')) ? 'checked' : ''; ?>>3-6 Años</input></br>
        <input type="radio" name="tipo" value="6-10" <?php echo (strstr($tipo, '6-10')) ? 'checked' : ''; ?>>6-10 Años</input></br><hr>

        <?php echo (!empty($marcaErr)) ? 'error: ' : ''; ?>
        <label>Marca: </label>
        <input type="text" required name="marca" value="<?php echo $marca; ?>"><hr>

        <label>Precio</label>: 
        <input type="number" name="precio" min="0" max="99999" step="0.01" value="<?php echo $precio; ?>">€<hr>

        <label>Unidades</label>: 
        <input type="number" name="unidades" min="0" max="999" step="1" value="<?php echo $unidades; ?>"><hr>

        <?php echo (!empty($imagenErr)) ? 'error: ' : ''; ?>
        <label>Imagen: </label>
        <input type="file" required name="imagen" class="form-control-file" id="imagen" accept="image/jpeg, image/png"><?php echo $imagenErr;?><hr>
        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <input type="hidden" name="imagenAnterior" value="<?php echo $imagenAnterior; ?>"/>
        <button type="submit" value="aceptar" class="btn btn-warning"> <span class="glyphicon glyphicon-refresh"></span>  Modificar</button>
        <a href="../index.php" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-left"></span> Volver</a>
    </fieldset>
</form>
<br><br><br>
<?php require_once "pie.php"; ?>
