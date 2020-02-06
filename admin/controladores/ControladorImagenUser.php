<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/tienda/admin/dirs.php";
    require_once CONTROLLER_PATH."ControladorUsuario.php";
    require_once UTILITY_PATH."funciones.php";

    
class ControladorImagen {
    
    static private $instancia = null;
    
    private function __construct() {
        //echo "Conector creado";
    }
    
    public static function getControlador() {
        if (self::$instancia == null) {
            self::$instancia = new ControladorImagen();
        }
        return self::$instancia;
    }

    function salvarImagen($imagen) {
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], IMAGE_PATH . $imagen)) {
            return true;
        }
        return false;
    }
    
    function eliminarImagen($imagen) {
        $fichero = IMAGE_PATH . $imagen;
        if (file_exists($fichero)) {
            unlink($fichero);
            return true;
        }
        return false;;
    }
    
    function actualizarImagen(){
        $imagenAnterior = trim($_POST["imagenAnterior"]);
        $extension = explode("/", $_FILES['imagen']['type']);
        $nombreImagen = md5($_FILES['imagen']['tmp_name'] . $_FILES['imagen']['name']) . "." . $extension[1];
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], ROOT_PATH . "imagenes/$nombreImagen")) {
           $nombreImagen = $imagenAnterior;
        }
        return $nombreImagen;
    }

}

?>