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
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header clearfix">
                <h2 class="pull-left">Fichas de los usuarios</h2>
            </div>
            <form class="form-inline" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="no_imprimir">
                <div class="form-group mx-sm-5 mb-2">
                    <label for="usuario" class="sr-only">Nombre</label>
                    <input type="text" class="form-control" id="buscar" name="usuario" placeholder="Nombre">
                </div>
                <?php
                echo "<a href='../utilidades/descargar.php?opcion=XML' type='button' class='btn btn-deep-orange' target='_blank'><i class='fas fa-cloud-download-alt'></i> XML</a>";
                echo "<a href='../utilidades/descargar.php?opcion=PDF' type='button' class='btn btn-outline-secondary waves-effect' target='_blank'><i class='fas fa-file-pdf'></i> PDF</a>";
                //echo "<a href='javascript:window.print()' type='button' class='btn btn-outline-info waves-effect'><i class='fas fa-print'></i> IMPRIMIR</a>";
                echo "<a href='vistas/create.php' class='btn aqua-gradient'><i class='fas fa-user-plus'></i> Añadir usuario</a>";
                //}
                ?>
                <?php
                //Codificacion admin:

                //echo hash("sha256","user");
                ?>
            </form>
        </div>
        <!-- Linea para dividir -->
        <div class="page-header clearfix">
        </div>
        <?php
        error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
        // Incluimos los ficheros que ncesitamos
        // Incluimos los directorios a trabajar
        require_once CONTROLLER_PATH . "ControladorUsuario.php";
        require_once CONTROLLER_PATH . "Paginador.php";
        require_once UTILITY_PATH . "funciones.php";

        if (!isset($_POST["usuario"])) {
            $nombre = "";
        } else {
            $nombre = filtrado($_POST["usuario"]);
        }
        
        $controlador = ControladorUsuario::getControlador();

        // Parte del paginador
        $pagina = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $enlaces = (isset($_GET['enlaces'])) ? $_GET['enlaces'] : 10;


            //Menu ADMINISTRADOR

            // Consulta a realizar
            $consulta = "SELECT * FROM usuarios WHERE nombre LIKE :nombre";
            $parametros = array(':nombre' => "%" . $nombre . "%");
            $limite = 100; // Limite del paginador
            $paginador  = new Paginador($consulta, $parametros, $limite);
            $resultados = $paginador->getDatos($pagina);
            if (count($resultados->datos) > 0) {
                echo "<table class='table'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th scope='col'>ID</th>";
                echo "<th scope='col'>NOMBRE</th>";
                echo "<th scope='col'>APELLIDOS</th>";
                echo "<th scope='col'>CORREO</th>";
                echo "<th scope='col'>CONTRASEÑA</th>";
                echo "<th scope='col'>TIPO</th>";
                echo "<th scope='col'>TELEFONO</th>";
                echo "<th scope='col'>IMAGEN</th>";
                echo "<th scope='col'>FECHA</th>";
                echo "<th id='no_imprimir' scope='col'>ACCIÓN</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                // Recorremos los registros encontrados
                foreach ($resultados->datos as $a) {
                    
                    $usuario = new Usuario($a->id, $a->nombre, $a->apellidos, $a->correo, $a->password, $a->tipo, $a->telefono, $a->imagen, $a->fecha);
                    // Pintamos cada fila
                    echo "<tr>";
                    echo "<td>" . base64_encode($usuario->getId()) . "</td>";
                    echo "<td>" . $usuario->getNombre() . "</td>";
                    echo "<td>" . $usuario->getApellidos() . "</td>";
                    echo "<td>" . $usuario->getCorreo() . "</td>";
                    echo "<td>" . base64_encode($usuario->getPassword()) . "</td>";
                    echo "<td>" . $usuario->getTipo() . "</td>";
                    echo "<td>" . $usuario->getTelefono() . "</td>";
                    echo "<td><img src='/tienda/admin/usuarios/imagenes/" . $usuario->getImagen() . "' width='80px' height='70px'></td>";
                    echo "<td>" . $usuario->getFecha() . "</td>";
                    echo "<td id='no_imprimir'>";
                    echo "<a href='vistas/read.php?id=" . encode($usuario->getId()) . "' title='Ver usuario' data-toggle='tooltip'><i class='fas fa-eye'></i></a>&nbsp;&nbsp;";
                    echo "<a href='vistas/update.php?id=" . encode($usuario->getId()) . "' title='Actualizar usuario' data-toggle='tooltip'><i class='fas fa-edit'></i></a>&nbsp;&nbsp;";
                    echo "<a href='vistas/delete.php?id=" . encode($usuario->getId()) . "' title='Borrar usuario' data-toggle='tooltip'><i class='fas fa-trash-alt'></i></a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "<ul class='pager' id='no_imprimir'>";
                echo $paginador->crearLinks($enlaces);
                echo "</ul>";
            } else {
                // Si no hay nada seleccionado
                echo "<p class='lead'><em>No se ha encontrado datos de usuarios.</em></p>";
            }
        ?>

    </div>
</div>
<div id="no_imprimir">
    <?php
    // Leemos la cookie
    /*if (isset($_COOKIE['CONTADOR'])) {
        echo $contador;
        echo $acceso;
    } else
        echo "Es tu primera visita hoy";
    */?>
</div>
<br><br><br>