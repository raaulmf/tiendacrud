<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once CONTROLLER_PATH."ControladorBD.php";

class ControladorAcceso {
    // Variable instancia para Singleton
    static private $instancia = null;
    
    // constructor--> Private por el patrón Singleton
    private function __construct() {
        //echo "Conector creado";
    }

    /**
     * Patrón Singleton. Ontiene una instancia de controlador
     * @return instancia del controlador
     */
    public static function getControlador() {
        if (self::$instancia == null) {
            self::$instancia = new ControladorAcceso();
        }
        return self::$instancia;
    }
    
    public function salirSesion() {
        // Recuperamos la información de la sesión
        session_start();
        // Y la eliminamos las variables de la sesión y cookies
        unset($_SESSION['USUARIO']);

        // ahora o las borramos todas o las destruimos, yo haré todo para que se vea
        session_unset();
        session_destroy();
    }
    
    

    // FUNCIÓN LOGIN
    public function procesarIdentificacion($correo, $password){

           //Aquí decimos que la contraseña del login va encriptada en sha256:
            //$password = hash("sha256",$password);

            // Conectamos a la base de datos
            $bd = ControladorBD::getControlador();
            $bd->abrirBD();
            // Consulta de usuarios por correo y contraseña
            $consulta = "SELECT * FROM usuarios WHERE correo=:correo and password=:password";
            $parametros = array(':correo' => $correo, ':password' => $password);

            // Obtenemos las filas directamente como objetos con las columnas de la tabla
            $res = $bd->consultarBD($consulta,$parametros);
            $filas=$res->fetchAll(PDO::FETCH_OBJ);
            
            if (count($filas) > 0) {

                // Abrimos sesión
                session_start();
                // Se almacenan los datos del usuario en un array de la sesión para después llamarlo en el apartado de "perfil"
                $usuario = new usuario($filas[0]->id, $filas[0]->nombre, $filas[0]->apellidos, $filas[0]->correo, $filas[0]->password, $filas[0]->tipo, $filas[0]->imagen, $filas[0]->telefono, $filas[0]->f_alta);
                $_SESSION['nombre'] = $usuario->getNombre();
               $_SESSION['apellido'] = $usuario->getApellidos();
               $_SESSION['tipo'] = $usuario->getTipo();
               $_SESSION['correo'] = $usuario->getCorreo();
               $_SESSION['imagen'] = $usuario->getImagen();
               $_SESSION['id'] = $usuario->getId();
                $_SESSION['USUARIO']['correo']=$correo;

                //Esta línea la puse porque me daba error el header en este punto con el PC de clase,
                //así que lo cambié por un refresh
                ?>
                <meta http-equiv="refresh" content="0; url=/tienda/index.php">
                <?php
                exit();              
                             
           } else {
               //Si alguno de los parametros de antes no son correctos, ir al error de credenciales incorrectos.
            ?>
            <meta http-equiv="refresh" content="0; url=/tienda/admin/vistas/no-user.php">
            <?php

                //<!-- Pie de la página web -->
                require_once VIEW_PATH."pie.php";
                exit();
            }
    }
}