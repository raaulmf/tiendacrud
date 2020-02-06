<?php
// Incluimos el controlador a los objetos a usar
require_once $_SERVER['DOCUMENT_ROOT'] . "/tienda/admin/usuarios/dirs.php";
require_once CONTROLLER_PATH . "ControladorUsuario.php";
require_once CONTROLLER_PATH . "ControladorImagenUser.php";
require_once UTILITY_PATH . "funciones.php";

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = decode($_GET["id"]);
    $controlador = ControladorUsuario::getControlador();
    $usuario = $controlador->buscarUsuario($id);
    if (is_null($usuario)) {
        // hay un error
        header("location: /tienda/admin/vistas/error.php");
        exit();
    }
}

// Los datos del formulario al procesar el sí.
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    $controlador = ControladorUsuario::getControlador();
    $usuario = $controlador->buscarUsuario($_POST["id"]);
    if ($controlador->borrarUsuario($_POST["id"])) {

        $controlador = ControladorImagen::getControlador();
        if ($controlador->eliminarImagen($usuario->getImagen())) {
            header("location: ../index.php");
            exit();
        } else {
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
<?php require_once VIEW_PATH . "cabecera.php"; ?>
<!-- Cuerpo de la página web -->
<div class="wrapper">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1>Borrar usuario</h1>
                </div>
                <!-- Muestro los datos del usuario-->
                <table class="table table-hover">
                    <tr>
                        <td>
                            <div class="form-group" class="align-left">
                                <!-- Muestro los datos del usuario-->
                                <div class="form-group">
                                    <b><label>NOMBRE</label></b>
                                    <p class="form-control-static"><?php echo $usuario->getNombre(); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="align-right">
                            <b><label>IMAGEN</label></b></br>
                            <img src='<?php echo "../imagenes/" . $usuario->getImagen() ?>' class='rounded' class='img-thumbnail' width='150' height='auto'>
                        </td>
                    </tr>
                </table>
                <table class="table table-hover">
                    <tr>
                        <td>
                            <b><label>APELLIDOS</label></b>
                            <p class="form-control-static"><?php echo $usuario->getApellidos(); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b><label>CORREO</label></b>
                            <p class="form-control-static"><?php echo $usuario->getCorreo(); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b><label>CONTRASEÑA</label></b>
                            <p class="form-control-static"><?php echo $usuario->getPassword(); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b><label>TIPO</label></b>
                            <p class="form-control-static"><?php echo $usuario->getTipo(); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b><label>TELEFONO</label></b>
                            <p class="form-control-static"><?php echo $usuario->getTelefono(); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b><label>FECHA</label></b>
                            <p class="form-control-static"><?php echo $usuario->getFecha(); ?></p>
                        </td>
                    </tr>
                </table>
                <!-- Me llamo a mi mismo pero pasando GET -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="alert alert-danger" role="alert">
                        <input type="hidden" name="id" value="<?php echo trim($id); ?>" />
                        <p>¿Está seguro que desea borrar este usuario?</p><br>
                        <p>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Borrar</button>
                            <a href="/tienda/admin/usuarios/index.php" class="btn btn-unique"><i class="fas fa-undo-alt"></i> Volver</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<br><br><br>
<!-- Pie de la página web -->
<?php require_once VIEW_PATH . "pie.php"; ?>