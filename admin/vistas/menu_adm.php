<style>
    div.span1 {
        padding: 20px;
        margin: 100px;
    }

    a#boton {
        padding: 50px;
    }
</style>
<?php
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
session_start();
if (!isset($_SESSION['USUARIO']['correo'])) {
    header("location: /tienda/login.php");
    exit();
} else if ($_SESSION['tipo'] != "ADMIN"){
    header("location: vistas/error.php");
        exit();
}

    echo '<h1>ESTE ES EL MENÚ DE ADMINISTRADOR</h1>';
    echo "<div class='span1'>";
    echo "<a id='boton' href='producto/index.php' class='btn btn-primary'>";
    echo '<i class="fab fa-product-hunt"></i>';
    echo '<span><strong>ADMINISTRAR PRODUCTOS</strong></span>';
    echo '</a>';
    echo '</div>';
    echo '<div class="span1">';
    echo '<a id="boton" href="usuarios/index.php" class="btn btn-primary">';
    echo '<i class="fas fa-user-cog"></i>';
    echo '<span><strong>ADMINISTRAR USUARIOS</strong></span>';
    echo '</a>';
    echo '</div>';
    echo '<div class="span1">';
    echo '<a id="boton" href="../index.php" class="btn btn-success">';
    echo '<i class="fas fa-shopping-basket"></i>';
    echo '<span><strong>ADMINISTRAR CATÁLOGO</strong></span>';
    echo '</a>';
    echo '</div>';
    echo '<div class="span1">';
    echo '<a id="boton" href="#" class="btn btn-danger">';
    echo '<i class="fas fa-sign-out-alt"></i>';
    echo '<span><strong>SALIR</strong></span>';
    echo '</a>';
    echo '</div>';
?>
<br><br><br>