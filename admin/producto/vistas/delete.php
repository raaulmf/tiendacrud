<?php
// Incluimos el controlador a los objetos a usar
require_once $_SERVER['DOCUMENT_ROOT']."/tienda/admin/producto/dirs.php";
require_once CONTROLLER_PATH."ControladorProducto.php";
require_once CONTROLLER_PATH."ControladorImagen.php";
require_once UTILITY_PATH."funciones.php";

// Obtenemos los datos del producto que nos vienen de la página anterior
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Cargamos el controlador de producto
    $id = decode($_GET["id"]);
    $controlador = ControladorProducto::getControlador();
    $producto = $controlador->buscarProducto($id);
    if (is_null($producto)) {
        header("location: /tienda/admin/vistas/error.php");
        exit();
    }
}

// Los datos del formulario al procesar el sí.
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    $controlador = ControladorProducto::getControlador();
    $producto = $controlador->buscarProducto($_POST["id"]);
    if ($controlador->borrarProducto($_POST["id"])) {
        //Se ha borrado y volvemos a la página principal
       // Debemos borrar la foto del producto
       $controlador = ControladorImagen::getControlador();
       if($controlador->eliminarImagen($producto->getImagen())){
            header("location: ../index.php");
            exit();
       }else{
            header("location: /tienda/admin/vistas/error.php");
            exit();
        }
    } else {
        header("location: /tienda/admin/vistas/error.php");
        exit();
    }
} 

?>
<!-- Cabecera de la página web -->
<?php require_once VIEW_PATH."cabecera.php"; ?>
<!-- Cuerpo de la página web -->
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1>Ficha de productos</h1>
                </div>
                <table class="table table-hover">
                    <tr>
                        <td>
                            <!-- Muestro los datos del usuario-->
                            <b><label>NOMBRE</label></b>
                            <p class="form-control-static"><?php echo $producto->getNombre(); ?></p>
                        </td>
                        <td>
                            <b><label>IMAGEN</label></b></br>
                            <img src='<?php echo "../imagen_producto/" . $producto->getImagen() ?>' class='rounded' class='img-thumbnail' width='100' height='auto'>
                        </td>
                    </tr>
                </table>
                <table class="table table-hover">
                    <tr>
                        <td>
                            <b><label>TIPO</label></b>
                            <p class="form-control-static"><?php echo $producto->getTipo(); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b><label>MARCA</label></b>
                            <p class="form-control-static"><?php echo $producto->getMarca(); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b><label>PRECIO</label></b>
                            <p class="form-control-static"><?php echo $producto->getPrecio()."€"; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b><label>UNIDADES</label></b>
                            <p class="form-control-static"><?php echo $producto->getUnidades(); ?></p>
                        </td>
                    </tr>
                </table>
                <!-- Me llamo a mi mismo para procesar el borrado -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="alert alert-danger" role="alert">
                        <input type="hidden" name="id" value="<?php echo trim($id); ?>" />
                        <p>¿Está seguro que desea borrar este producto?</p><br>
                        <p>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Borrar</button>
                            <a href="/tienda/admin/producto/index.php" class="btn btn-unique"><i class="fas fa-undo-alt"></i> Volver</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>        
    </div>
</div>
<br><br><br>
<!-- Pie de la página web -->
<?php require_once VIEW_PATH."pie.php"; ?>
