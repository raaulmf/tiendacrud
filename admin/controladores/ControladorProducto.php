<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorProducto
 *
 * @author link
 */

require_once MODEL_PATH."Producto.php";
require_once CONTROLLER_PATH."ControladorBD.php";
require_once UTILITY_PATH."funciones.php";

class ControladorProducto {

     // Variable instancia para Singleton
    static private $instancia = null;
    
    // constructor--> Private por el patrón Singleton
    private function __construct() {
        //echo "Conector creado";
    }
    
    /**
     * Patrón Singleton. Ontiene una instancia del Manejador de la BD
     * @return instancia de conexion
     */
    public static function getControlador() {
        if (self::$instancia == null) {
            self::$instancia = new ControladorProducto();
        }
        return self::$instancia;
    }
    
    /**
     * Lista el alumnado según el nombre o marca
     * @param type $nombre
     * @param type $marca
     */
    public function listarProductos($nombre, $marca){
        // Creamos la conexión a la BD
        $lista=[];
        $bd = ControladorBD::getControlador();
        $bd->abrirBD();
        // creamos la consulta pero esta vez paremtrizada
        $consulta = "SELECT * FROM productos WHERE nombre LIKE :nombre OR marca LIKE :marca";
        $parametros = array(':nombre' => "%".$nombre."%", ':marca' => "%".$marca."%");
        // Obtenemos las filas directamente como objetos con las columnas de la tabla
        $res = $bd->consultarBD($consulta,$parametros);
        $filas=$res->fetchAll(PDO::FETCH_OBJ);
        //var_dump($filas);
        if (count($filas) > 0) {
            foreach ($filas as $l) {
                $producto = new Producto($l->id, $l->nombre, $l->tipo, $l->marca, $l->precio, $l->unidades, $l->imagen);
                // Lo añadimos
                $lista[] = $producto;
            }
            $bd->cerrarBD();
            return $lista;
        }else{
            return null;
        }    
    }
    
    public function almacenarProducto($nombre, $tipo, $marca, $precio, $unidades, $imagen){
        //$alumno = new Alumno("",$dni, $nombre, $email, $password, $idioma, $matricula, $lenguaje, $fecha, $imagen);
        $bd = ControladorBD::getControlador();
        $bd->abrirBD();
        $consulta = "INSERT INTO productos (nombre, tipo, marca, 
            precio, unidades, imagen) VALUES (:nombre, :tipo, :marca, :precio, :unidades, 
            :imagen)";
        
        $parametros= array(':nombre'=>$nombre, ':tipo'=>$tipo,':marca'=>$marca,
                            ':precio'=>$precio, ':unidades'=>$unidades, ':imagen'=>$imagen);
        $estado = $bd->actualizarBD($consulta,$parametros);
        $bd->cerrarBD();
        return $estado;
    }
    
    public function buscarProducto($id){ 
        $bd = ControladorBD::getControlador();
        $bd->abrirBD();
        $consulta = "SELECT * FROM productos WHERE id = :id";
        $parametros = array(':id' => $id);
        $filas = $bd->consultarBD($consulta, $parametros);
        $res = $bd->consultarBD($consulta,$parametros);
        $filas=$res->fetchAll(PDO::FETCH_OBJ);
        if (count($filas) > 0) {
            foreach ($filas as $l) {
                $producto = new Producto($l->id, $l->nombre, $l->tipo, $l->marca, $l->precio, $l->unidades, $l->imagen);
                // Lo añadimos
                $lista[] = $producto;
            }
            $bd->cerrarBD();
            return $producto;
        }else{
            return null;
        }    
    }

    public function buscarProductoNombre($nombre){ 
        $bd = ControladorBD::getControlador();
        $bd->abrirBD();
        $consulta = "SELECT * FROM productos WHERE nombre = :nombre";
        $parametros = array(':nombre' => $nombre);
        $filas = $bd->consultarBD($consulta, $parametros);
        $res = $bd->consultarBD($consulta,$parametros);
        $filas=$res->fetchAll(PDO::FETCH_OBJ);
        if (count($filas) > 0) {
            foreach ($filas as $l) {
                $producto = new Producto($$l->id, $l->nombre, $l->tipo, $l->marca, $l->precio, $l->unidades, $l->imagen);
                // Lo añadimos
                $lista[] = $producto;
            }
            $bd->cerrarBD();
            return $producto;
        }else{
            return null;
        }    
    }
    
    public function borrarProducto($id){ 
        $estado=false;
        // Borro el alumno de la
        $bd = ControladorBD::getControlador();
        $bd->abrirBD();
        $consulta = "DELETE FROM productos WHERE id = :id";
        $parametros = array(':id' => $id);
        $estado = $bd->actualizarBD($consulta,$parametros);
        $bd->cerrarBD();
        return $estado;
    }
    
    public function actualizarProducto($id, $nombre, $tipo, $marca, $precio, $unidades, $imagen){
       // $alumno = new Alumno($id,$dni, $nombre, $email, $password, $idioma, $matricula, $lenguaje, $fecha, $imagen);
        $bd = ControladorBD::getControlador();
        $bd->abrirBD();
        $consulta = "UPDATE productos SET nombre=:nombre, tipo=:tipo, marca=:marca, precio=:precio, unidades=:unidades, imagen=:imagen 
            WHERE id=:id";
        $parametros= array(':id' => $id, ':nombre'=>$nombre, ':tipo'=>$tipo,':marca'=>$marca,
                            ':precio'=>$precio, ':unidades'=>$unidades, ':imagen'=>$imagen);
        $estado = $bd->actualizarBD($consulta,$parametros);
        $bd->cerrarBD();
        return $estado;
    }
    
}
