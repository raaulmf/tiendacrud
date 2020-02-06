 <!-- AQUÍ EMPIEZA EL NAVBAR-->
 <ul class="nav justify-content-center grey lighten-4 py-4">
   <img src="/tienda/admin/usuarios/imagenes/RR.png" alt="RR" width="40px" id="rr">
   <li class="nav-item">
     <a class="nav-link active" href=<?php echo "/tienda/index.php"; ?>><i class="fas fa-home"></i>Inicio</a>
   </li>
   <?php
    error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
    // Incluimos los ficheros que ncesitamos
    // Incluimos los directorios a trabajar
    require_once CONTROLLER_PATH . "ControladorUsuario.php";
    require_once CONTROLLER_PATH . "Paginador.php";
    require_once UTILITY_PATH . "funciones.php";

    if (!isset($_POST["correo"])) {
      $nombre = "";
    } else {
      $nombre = filtrado($_POST["correo"]);
    }

    $controlador = ControladorUsuario::getControlador();

    // Parte del paginador
    $pagina = (isset($_GET['page'])) ? $_GET['page'] : 1;
    $enlaces = (isset($_GET['enlaces'])) ? $_GET['enlaces'] : 10;


    //Menu ADMINISTRADOR

    // Consulta a realizar -- esto lo cambiaré para la semana que viene
    $consulta = "SELECT * FROM usuarios WHERE correo LIKE :correo";
    $parametros = array(':correo' => "%" . $correo . "%");
    $limite = 3; // Limite del paginador
    $paginador  = new Paginador($consulta, $parametros, $limite);
    $resultados = $paginador->getDatos($pagina);
    foreach ($resultados->datos as $a) {

      $usuario = new Usuario($a->id, $a->nombre, $a->apellidos, $a->correo, $a->password, $a->tipo, $a->telefono, $a->imagen, $a->fecha);
      // Pintamos cada fila
      $usuario->getId();
      $usuario->getNombre();
      $usuario->getApellidos();
      $usuario->getCorreo();
      base64_encode($usuario->getPassword());
      $usuario->getTipo();
      $usuario->getTelefono();
      $usuario->getImagen();
      $usuario->getFecha();
    }
    // Abrimos las sesiones para leerla
    session_start();
    if ($_SESSION['tipo'] != "ADMIN") {
      // Menú normal
    } else {
      // Menu de administrador
      echo '<li class="nav-item">';
      echo '<a class="nav-link" href="#"><i class="fas fa-tools"></i> Administrador</a>';
      echo '<ul>';
      echo '<li><a href="/tienda/admin"><i class="fas fa-arrow-right"></i>Panel de administración</a></li>';
      //echo '<li><a href=""><i class="fas fa-arrow-right"></i> Menu admin 2</a></li>';
      echo '</ul>';
      echo '</li>';
    }
    ?>
   <!--Para todos-->
   <li class="nav-item">
     <a class="nav-link" href="/tienda/index.php">Catálogo</a>
   </li>
   <!--
   <li class="nav-item">
     <a class="nav-link" href="/tienda/admin/vistas/informacion.php">Información</a>
   </li>-->

   <?php
    if (!isset($_SESSION['USUARIO']['correo'])) {
      echo '<li class="nav-item">';
      echo '<a class="nav-link2" href="/tienda/login.php"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a>';
      echo '</li>';
    } else {
      echo '<li><a href="/tienda/vistas/perfil.php?id=' . encode($usuario->getId()) . '"><span class="glyphicon glyphicon-user"></span> ' . $_SESSION['USUARIO']['correo'] . '</a></li>';
      echo '<li><a href="/tienda/login.php"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>';
    }
    ?>
 </ul>
 <br><br>