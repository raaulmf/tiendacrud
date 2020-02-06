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
<h2 align="center">GESTIÓN DE LOS PRODUCTOS</h2><hr>

<div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header clearfix">
                    <h2 class="pull-left">Fichas de los articulos</h2>
                </div>
                <form class="form-inline" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="no_imprimir">
                    <div class="form-group mx-sm-5 mb-2">
                        <label for="producto" class="sr-only">Nombre o marca</label>
                        <input type="text" class="form-control" id="buscar" name="producto" placeholder="Nombre o Marca">
                    </div>
                    <a href="../utilidades/descargar.php?opcion=XMLProd" class="btn btn-primary btn-info"><span class="glyphicon glyphicon-floppy-save"></span> XML</a>
                    <a href="../utilidades/descargar.php?opcion=PDFProd" class="btn btn-primary btn-warning"><span class="glyphicon glyphicon-floppy-save"></span> PDF</a>
                    <a href="/tienda/admin/producto/vistas/crear.php"class="btn btn-primary btn-success">Crear <span class="glyphicon glyphicon-pencil"></span></a>
                </form>
            </div>
            <!-- Linea para dividir -->
            <div class="page-header clearfix">        
            </div>
            <?php
            // Incluimos los ficheros que ncesitamos
            // Incluimos los directorios a trabajar
            require_once CONTROLLER_PATH."ControladorProducto.php";
            require_once CONTROLLER_PATH . "Paginador.php";
            require_once UTILITY_PATH."funciones.php";

            if (!isset($_POST["producto"])) {
                $nombre = "";
                $marca = "";
            } else {
                $nombre = filtrado($_POST["producto"]);
                $marca = filtrado($_POST["producto"]);
            }
            // Cargamos el controlador de producto
            $controlador = ControladorProducto::getControlador();
            
            // Parte del paginador
            $pagina = ( isset($_GET['page']) ) ? $_GET['page'] : 1;
            $enlaces = ( isset($_GET['enlaces']) ) ? $_GET['enlaces'] : 10;



             // Consulta para el buscador
             $consulta = "SELECT * FROM productos WHERE nombre LIKE :nombre OR marca LIKE :marca";
             $parametros = array(':nombre' => "%".$nombre."%", ':nombre' => "%".$nombre."%", ':marca' => "%".$marca."%");
             $limite = 4; // Limite del paginador
             $paginador  = new Paginador($consulta, $parametros, $limite);
             $resultados = $paginador->getDatos($pagina);


            // Si hay filas (no nulo), pues mostramos la tabla
            if(count( $resultados->datos)>0){
                echo "<table class='table table-bordered table-striped'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Nombre</th>";
                echo "<th>Tipo</th>";
                echo "<th>Marca</th>";
                echo "<th>Precio</th>";
                echo "<th>Unidades</th>";
                echo "<th>Imagen</th>";
                echo "<th>Acción</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                // Recorremos los registros encontrados
                foreach ($resultados->datos as $l) {
                    $producto = new Producto($l->id, $l->nombre, $l->tipo, $l->marca, $l->precio, $l->unidades, $l->imagen);
                    // Pintamos cada fila
                    echo "<tr>";
                    echo "<td>" . $producto->getNombre() . "</td>";
                    echo "<td>De " . $producto->getTipo() . " Años</td>";
                    echo "<td>" . $producto->getMarca() . "</td>";
                    echo "<td>" . $producto->getPrecio() . "€</td>";
                    echo "<td>" . $producto->getUnidades() . "</td>";
                    echo "<td><img src='imagen_producto/".$producto->getImagen()."' width='48px' height='48px'></td>";
                    echo "<td>";
                    echo "<a href='vistas/read.php?id=" . encode($producto->getId()) . "' title='Ver usuario' data-toggle='tooltip'><i class='fas fa-eye'></i></a>&nbsp;&nbsp;";
                    echo "<a href='vistas/update.php?id=" . encode($producto->getId()) . "' title='Actualizar usuario' data-toggle='tooltip'><i class='fas fa-edit'></i></a>&nbsp;&nbsp;";
                    echo "<a href='/tienda/admin/producto/vistas/delete.php?id=" . encode($producto->getId()) . "' title='Borrar usuario' data-toggle='tooltip'><i class='fas fa-trash-alt'></i></a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo $paginador->crearLinks($enlaces);
                echo "</ul>";
            } else {
                // Si no hay nada seleccionado
                echo "<p class='lead'><em>No se ha encontrado articulos.</em></p>";
            }
            ?>

        </div>
    </div>
    <?php
        // Leemos la cookie
        if(isset($_COOKIE['CONTADOR'])){
            echo $contador;
            echo $acceso;
        }
        else
            echo "Es tu primera visita hoy";
    ?>
    <br><br><br> 