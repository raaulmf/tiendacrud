<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/tienda/admin/usuarios/dirs.php";
require_once CONTROLLER_PATH . "ControladorUsuario.php";
require_once CONTROLLER_PATH . "ControladorImagen.php";
require_once UTILITY_PATH . "funciones.php";

// Variables
$nombre = $apellidos = $correo = $password = $tipo = $telefono = $imagen = ""; $fecha = "";
$nombreErr = $apellidosErr = $correoErr = $passwordErr = $tipoErr = $telefonoErr = $imagenErr = ""; $fechaErr = "";

    // Procesamos el formulario cuando se pase por el botón de aceptar
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["aceptar"]) {

        // Procesamos el nombre que tendrá un pattern
        $nombreVal = filtrado(($_POST["nombre"]));
        if (empty($nombreVal)) {
            $nombreErr = "Por favor introduzca un nombre válido con solo carávteres alfabéticos.";
            
        } elseif (!preg_match("/([^\s][A-zÀ-ž\s]+$)/", $nombreVal)) {
            $nombreErr = "Por favor introduzca un nombre válido con solo carávteres alfabéticos.";
        } else {
            $nombre = $nombreVal;
        }

        // Buscamos coincidencias en el nombre de usuario en la BBDD con el que quiere registrarse
        $controlador = ControladorUsuario::getControlador();
        $usuario = $controlador->buscarUsuarioNombre($nombreVal);
        if (isset($usuario)) {
            $nombreErr = '¡ERR0R! ----- El nombre "' . $nombreVal . '" ya está registrado en esta web.';
        } else {
            $nombre = $nombreVal;
        }

        // Procesamos apellidos
        if (isset($_POST["apellidos"])) {
            $apellidos = filtrado($_POST["apellidos"]);
        } else {
            $apellidosErr = "Apellido no válido";
        }

        // Procesamos el correo
        $correoVal = filtrado(($_POST["correo"]));
        if (empty($nombreVal)) {
            $correoErr = "Por favor introduzca un correo válido.";
        } else {
            $correo = $correoVal;
        }

        // Buscamos que no exista el correo exactamente igual que el nombre
        $controlador = ControladorUsuario::getControlador();
        $usuario = $controlador->buscarUsuarioCorreo($correoVal);
        if (isset($usuario)) {
            $correoErr = '¡ERR0R! ----- El correo "' . $correoVal . '" ya está registrado en esta web.';
        } else {
            $correo = $correoVal;
        }

        // Procesamos la contraseña
        if (isset($_POST["password"])) {
            $password = filtrado($_POST["password"]);
        } else {
            $passwordErr = "Contraseña no válida";
        }

        // Procesamos tipo
        // Aquí pongo que si se le selecciona un tipo que se ponga el seleccionado y si no se selecciona,
        // por defecto es USER.
        if (isset($_POST["tipo"])) {
            $tipo = filtrado($_POST["tipo"]);
        } else {
            $tipo = "USER";
        }

        // Procesamos telefono (tiene pattern en el input)
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
            $fecha = $hoy;
        }

        // Procesamos la imagen
        if ($_FILES['imagen']['size'] > 0) {
            $propiedades = explode("/", $_FILES['imagen']['type']);
            $extension = $propiedades[1];
            $tam_max = 5000000; // 5 MB
            $tam = $_FILES['imagen']['size'];
            $mod = true;

            // La extensión debe ser jpeg o png, sino, error
            if ($extension != "jpg" && $extension != "jpeg" && $extension != "png") {
                $mod = false;
                $imagenErr = "Formato debe ser jpg/jpeg/png";
            }
            // Tamaño máximo
            if ($tam > $tam_max) {
                $mod = false;
                $imagenErr = "Tamaño superior al limite de: " . ($tam_max / 1000) . " KBytes";
            }

            //Se codifica en md5 la imagen para que sea imposible que coincidan. se añade el nombre y la fecha y hora al md5
            if ($mod) {
                $imagen = md5($_FILES['imagen']['tmp_name'] . $_FILES['imagen']['name'] . time()) . "." . $extension;
                $controlador = ControladorImagen::getControlador();
                if (!$controlador->salvarImagen($imagen)) {
                    $imagenErr = "Error al procesar la imagen y subirla al servidor";
                }
            }
        }

        // Si todas las variables de error están vacías, para adelante con todo
        if (
            empty($nombreErr) && empty($apellidosErr) && empty($correoErr) && empty($passwordErr) && empty($tipoErr) &&
            empty($telefonoErr) && empty($imagenErr) && empty($fechaErr)
        ) {
            // Llamamos al controlador y almacenamos en la función los parámetros que se pasen:
            $controlador = ControladorUsuario::getControlador();
            $estado = $controlador->almacenarUsuario($nombre, $apellidos, $correo, $password, $tipo, $telefono, $imagen, $fecha);
            if ($estado) {
                // Si es correcto:
                alerta("Usuario creado con éxito");
                header("location: ../index.php");
                exit();
            } else {
                // Si hay algún error:
                header("location: error.php");
                exit();
            }
        }
    }
?>

<?php require_once VIEW_PATH . "cabecera.php"; ?>

<div>
    <div>
        <div>
            <div class="col-md-12">
                <div>
                    <h2>Formulario de registro</h2>
                </div>
                <p>Por favor rellene este formulario para registrarse como usuario en el sistema.</p>
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

<?php require_once VIEW_PATH . "pie.php"; ?>