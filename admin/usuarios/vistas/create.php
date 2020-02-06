<?php
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
session_start();
if (!isset($_SESSION['USUARIO']['correo'])) {
    header("location: /tienda/login.php");
    exit();
} else if ($_SESSION['tipo'] != "ADMIN"){
    header("location: /tienda/admin/vistas/error.php");
        exit();
}
// Incluimos los directorios a trabajar
require_once $_SERVER['DOCUMENT_ROOT'] . "/tienda/admin/usuarios/dirs.php";
require_once CONTROLLER_PATH . "ControladorUsuario.php";
require_once CONTROLLER_PATH . "ControladorImagenUser.php";
require_once UTILITY_PATH . "funciones.php";

// Variables temporales
$nombre = $apellidos = $correo = $password = $tipo = $telefono = $imagen = ""; $fecha = "";
$nombreErr = $apellidosErr = $correoErr = $passwordErr = $tipoErr = $telefonoErr = $imagenErr = ""; $fechaErr = "";

    // Procesamos el formulario al pulsar el botón aceptar de esta ficha
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["aceptar"]) {

        // Procesamos el nombre
        $nombreVal = filtrado(($_POST["nombre"]));
        if (empty($nombreVal)) {
            $nombreErr = "Por favor introduzca un nombre válido con solo carávteres alfabéticos.";
            
        } elseif (!preg_match("/([^\s][A-zÀ-ž\s]+$)/", $nombreVal)) {
            $nombreErr = "Por favor introduzca un nombre válido con solo carávteres alfabéticos.";
        } else {
            $nombre = $nombreVal;
        }

        // Buscamos que no exista el usuario
        $controlador = ControladorUsuario::getControlador();
        $usuario = $controlador->buscarUsuarioNombre($nombreVal);
        if (isset($usuario)) {
            alerta(print_r($usuario->getNombre()));
            $nombreErr = "Ya existe un usuario con nombre:" . $nombreVal . " en la Base de Datos";
        } else {
            $nombre = $nombreVal;
        }

        // Procesamos apellidos
        if (isset($_POST["apellidos"])) {
            $apellidos = filtrado($_POST["apellidos"]);
        } else {
            $apellidosErr = "Apellido no válido";
        }

        // Procesamos correo
        if (isset($_POST["correo"])) {
            $correo = filtrado($_POST["correo"]);
        } else {
            $correoErr = "Correo no válido";
        }

        // Procesamos la contraseña
        if (isset($_POST["password"])) {
            //$password = hash("sha256",filtrado($_POST["password"])); PARA QUE LA CONTRASEÑA DE ALMACENE CODIFICADA EN SHA256
            $password = filtrado($_POST["password"]);
        } else {
            $passwordErr = "Contraseña no válida";
        }

        // Procesamos tipo
        if (isset($_POST["tipo"])) {
            $tipo = filtrado($_POST["tipo"]);
        } else {
            $tipoErr = "Debe elegir al menos una opción.";
        }

        // Procesamos telefono
        if (isset($_POST["telefono"])) {
            $telefono = filtrado($_POST["telefono"]);
        } else {
            $telefonoErr = "El número de teléfono tiene que tener 9 cifras.";
        }

        // Procesamos fecha
        $fecha = date("d-m-Y", strtotime(filtrado($_POST["fecha"])));
        $hoy = date("d-m-Y", time());

        // Comparamos las fechas
        $fecha_mat = new DateTime($fecha);
        $fecha_hoy = new DateTime($hoy);
        $interval = $fecha_hoy->diff($fecha_mat);

        if($interval->format('%R%a días')>0){
            $fechaErr = "La fecha no puede ser superior a la fecha actual";
            $errores[]=  $fechaErr;

        }else{
            $fecha = date("d/m/Y",strtotime($fecha));
        }

        // Procesamos la imagen
        if ($_FILES['imagen']['size'] > 0) {
            $propiedades = explode("/", $_FILES['imagen']['type']);
            $extension = $propiedades[1];
            $tam_max = 5000000; // 5 MB
            $tam = $_FILES['imagen']['size'];
            $mod = true; // Si vamos a modificar

            // Si no coicide la extensión
            if ($extension != "jpg" && $extension != "jpeg" && $extension != "png") {
                $mod = false;
                $imagenErr = "Formato debe ser jpg/jpeg/png";
            }
            // si no tiene el tamaño
            if ($tam > $tam_max) {
                $mod = false;
                $imagenErr = "Tamaño superior al limite de: " . ($tam_max / 1000) . " KBytes";
            }

            // Si todo es correcto, mod = true
            if ($mod) {
                // salvamos la imagen
                $imagen = md5($_FILES['imagen']['tmp_name'] . $_FILES['imagen']['name'] . time()) . "." . $extension;
                $controlador = ControladorImagen::getControlador();
                if (!$controlador->salvarImagen($imagen)) {
                    $imagenErr = "Error al procesar la imagen y subirla al servidor";
                }
            }
        }
        // Chequeamos los errores antes de insertar en la base de datos
        if (
            empty($nombreErr) && empty($apellidosErr) && empty($correoErr) && empty($passwordErr) && empty($tipoErr) &&
            empty($telefonoErr) && empty($imagenErr) && empty($fechaErr)
        ) {
            // Creamos el controlador de alumnado
            $controlador = ControladorUsuario::getControlador();
            $estado = $controlador->almacenarUsuario($nombre, $apellidos, $correo, $password, $tipo, $telefono, $imagen, $fecha);
            if ($estado) {
                //El registro se ha lamacenado corectamente
                alerta("Usuario creado con éxito");
                header("location: ../index.php");
                exit();
            } else {
                header("location: /tienda/admin/vistas/error.php");
                exit();
            }
        }
    }
?>

<!-- Cabecera de la página web -->
<?php require_once VIEW_PATH . "cabecera.php"; ?>
<!-- Cuerpo de la página web -->
<div>
    <div>
        <div>
            <div class="col-md-12">
                <div>
                    <h2>Crear Usuario</h2>
                </div>
                <p>Por favor rellene este formulario para añadir un nuevo usuario a la base de datos de usuarios.</p>
                <!-- Formulario-->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

                    <!-- Nombre -->
                    <div class="md-form <?php echo (!empty($nombreErr)) ? 'error: ' : ''; ?>">
                        <input placeholder="Nombre" required name="nombre" type="text" id="inputPlaceholderEx" class="form-control" value="<?php echo $nombre; ?>" pattern="([^\s][A-zÀ-ž\s]+)" title="El nombre no puede contener números" minlength="3">
                        <span class="help-block"><?php echo $nombreErr; ?></span>
                    </div>
                    </br>
                    <!-- Apellidos -->
                    <div class="md-form <?php echo (!empty($apellidosErr)) ? 'error: ' : ''; ?>">
                        <input placeholder="Apellidos" required name="apellidos" type="text" id="inputPlaceholderEx" class="form-control" value="<?php echo $apellidos; ?>" pattern="([^\s][A-zÀ-ž\s]+)" title="Los apellidos no puede contener números" minlength="3">
                        <span class="help-block"><?php echo $apellidosErr; ?></span>
                    </div>
                    </br>
                    <!-- Correo -->
                    <div class="form-group <?php echo (!empty($correoErr)) ? 'error: ' : ''; ?>">
                        <b><label>Correo</label></b>
                        <input type="email" required name="correo" class="form-control" value="<?php echo $correo; ?>" minlength="1">
                        <span class="help-block"><?php echo $correoErr; ?></span>
                    </div>
                    </br>
                    <!-- Contraseña -->
                    <div class="form-group <?php echo (!empty($passwordErr)) ? 'error: ' : ''; ?>">
                        <b><label>Contraseña</label></b>
                        <input type="password" required name="password" class="form-control" value="<?php echo $password; ?>" minlength="1">
                        <span class="help-block"><?php echo $passwordErr; ?></span>
                    </div>
                    </br>
                    <!--Tipo-->
                    <b><label>Tipo</label></b>
                    <select name="tipo" class="custom-select custom-select-sm">
                        <option value="ADMIN" <?php echo (strstr($tipo, 'ADMIN')) ? 'selected' : ''; ?>>ADMIN</option>
                        <option value="USER" <?php echo (strstr($tipo, 'USER')) ? 'selected' : ''; ?>>USER</option>
                    </select>
                    </br></br></br>
                    <!-- Telefono -->
                    <div class="form-group <?php echo (!empty($telefonoErr)) ? 'error: ' : ''; ?>">
                        <b><label>Telefono</label></b>
                        <input pattern="([0-9]{9})" type="tel" required name="telefono" class="form-control" value="<?php echo $telefono; ?>" minlength="9" maxlength="9" title="El teléfono tiene que tener 9 números. Sin letras.">
                        <span class="help-block"><?php echo $telefonoErr; ?></span>
                    </div>
                    </br>
                    <!-- IMAGEN-->
                    <div class="form-group <?php echo (!empty($imagenErr)) ? 'error: ' : ''; ?>">
                        <b><label>IMAGEN</label></b>
                        <!-- Solo acepto imagenes jpg -->
                        <input type="file" required name="imagen" class="form-control-file" id="imagen" accept="image/jpeg, image/png">
                        <span class="help-block"><?php echo $imagenErr; ?></span>
                    </div>
                    </br>
                    <!-- Fecha -->
                    <div class="form-group <?php echo (!empty($fechaErr)) ? 'error: ' : ''; ?>">
                        <b><label>Fecha</label></b>
                        <input type="date" required name="fecha" class="form-control" value="<?php echo $fecha; ?>" minlength="1">
                        <span class="help-block"><?php echo $fechaErr; ?></span>
                    </div>
                    <!-- Botones -->
                    <button type="submit" name="aceptar" value="aceptar" class="btn peach-gradient"><i class="fas fa-save"></i> Aceptar</button>
                    <button type="reset" value="reset" class="btn btn-brown"><i class="fas fa-broom"></i> Limpiar</button>
                    <a href="/tienda/admin/usuarios/index.php" class="btn btn-unique"><i class="fas fa-undo-alt"></i> Volver</a>
                </form>
            </div>
        </div>
    </div>
</div>
<br><br><br>
<!-- Pie de la página web -->
<?php require_once VIEW_PATH . "pie.php"; ?>