<?php

//seguro para no poder acceder por url en caso de no estar logueado.

error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
session_start();
if (!isset($_SESSION['USUARIO']['correo'])) {
    header("location: /tienda/login.php");
    exit();
} else if ($_SESSION['tipo'] != "ADMIN"){
    header("location: /tienda/admin/vistas/error.php");
        exit();
}

require_once $_SERVER['DOCUMENT_ROOT']."/tienda/admin/producto/dirs.php";
require_once CONTROLLER_PATH."ControladorProducto.php";
require_once CONTROLLER_PATH."ControladorImagen.php";
require_once UTILITY_PATH."funciones.php";


$nombre = $tipo = $marca = $precio = $unidades = $imagen = "";
$nombreErr = $tipoErr = $marcaErr = $precioErr = $unidadesErr = $imagenErr = "";

if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["aceptar"]){

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


    if(isset($_POST["unidades"])){
        $unidades = filtrado($_POST["unidades"]);
    }
    
//IMAGEN
    $propiedades = explode("/", $_FILES['imagen']['type']);
    $extension = $propiedades[1];
    $tam_max = 1000000; // 1MB
    $tam = $_FILES['imagen']['size'];
    $mod = true;

    // Si no coicide la extensión
    if ($extension != "jpg" && $extension != "jpeg" && $extension != "png") {
        $mod = false;
        $imagenErr = "Formato debe ser jpg/jpeg/png";
    }

    if($tam>$tam_max){
        $mod = false;
        $imagenErr= "Tamaño superior al limite de: ". ($tam_max/1000). " KBytes";
    }
    

    if($mod){
        $imagen = md5($_FILES['imagen']['tmp_name'] . $_FILES['imagen']['name'].time()) . "." . $extension;
        $controlador = ControladorImagen::getControlador();
        if(!$controlador->salvarImagen($imagen)){
            $imagenErr = "Error al procesar la imagen y subirla al servidor";
        }
    }

    //Si no hay ningún error almacenamos el producto y sino muestra la alerta de fallo

    if(empty($nombreErr) && empty($tipoErr) && empty($marcaErr) && empty($precioErr) && empty($unidadesErr) && empty($imagenErr)){
        $controlador = ControladorProducto::getControlador();
        $estado = $controlador->almacenarProducto($nombre, $tipo, $marca, $precio, $unidades, $imagen);
        if($estado){
            header("location: ../index.php");
            exit();
        }else{
            header("location: /tienda/admin/vistas/error.php");
            exit();
        }
    }else{
        alerta("Hay errores al procesar el formulario, revise los errores");
    }



}


?>

<?php require_once "cabecera.php"; ?>



<h2>Crear Producto</h2>
<p>Por favor rellene los datos para introducir un nuevo producto</p>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
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
        <input type="number" name="precio" min="0" max="99999" step="0.01" value="0"><hr>

        <label>Unidades</label>: 
        <input type="number" name="unidades" min="0" max="999" step="1" value="0"><hr>

        <?php echo (!empty($imagenErr)) ? 'error: ' : ''; ?>
        <label>Imagen: </label>
        <input type="file" required name="imagen" class="form-control-file" id="imagen" accept="image/jpeg, image/png"><?php echo $imagenErr;?><hr>
        <button type="submit" name= "aceptar" value="aceptar" class="btn btn-success"> <span class="glyphicon glyphicon-floppy-save"></span>  Aceptar</button>
        <button type="reset" value="reset" class="btn btn-info"> <span class="glyphicon glyphicon-repeat"></span>  Limpiar</button>
        <a href="../index.php" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-left"></span> Cancelar</a>
    </fieldset>
</form>
<br><br><br>
<?php require_once "pie.php"; ?>
