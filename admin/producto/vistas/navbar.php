 <!-- AQUÍ EMPIEZA EL NAVBAR-->
 <ul class="nav justify-content-center grey lighten-4 py-4">
   <img src="/tienda/admin/usuarios/imagenes/RR.png" alt="RR" width="40px" id="rr">
   <li class="nav-item">
     <a class="nav-link active" href=<?php echo "/tienda/index.php"; ?>><i class="fas fa-home"></i>Inicio</a>
   </li>
   <?php
    // Abrimos las sesiones para leerla
    error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
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
      echo '<li><a href="#"><span class="glyphicon glyphicon-user"></span> ' . $_SESSION['USUARIO']['correo'] . '</a></li>';
      echo '<li><a href="/tienda/login.php"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>';
    }
    ?>
 </ul>
 <br><br>