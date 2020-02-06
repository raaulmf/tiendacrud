<?php
// Incluimos el controlador a los objetos a usar
require_once $_SERVER['DOCUMENT_ROOT'] . "/tienda/admin/producto/dirs.php";
require_once CONTROLLER_PATH . "ControladorProducto.php";
require_once UTILITY_PATH . "funciones.php";

// Comprobamos la existencia del parámetro id antes de usarlo
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Cargamos el controlador de productos
    $id = decode($_GET["id"]);
    $controlador = ControladorProducto::getControlador();
    $producto = $controlador->buscarProducto($id);
    if (is_null($producto)) {
        // hay un error
        header("location: /tienda/admin/vistas/error.php");
        exit();
    }
}
?>

<!-- Cabecera de la página web -->
<?php require_once VIEW_PATH . "cabecera.php"; ?>

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
                <p><a href="../index.php" class="btn btn-info"><i class="fas fa-check-circle"></i> Aceptar</a></p>
                <!--<a href="../utilidades/descargar.php?opcion=PDFUsuario&id=<?php echo $_GET["id"] ?>" type="button" class="btn btn-outline-secondary waves-effect" target="_blank"><i class="fas fa-file-pdf"></i> Descargar en PDF este usuario</a>-->
            </div>
        </div>
    </div>
</div>
<br><br><br>
<!-- Pie de la página web -->
<?php require_once VIEW_PATH . "pie.php"; ?>