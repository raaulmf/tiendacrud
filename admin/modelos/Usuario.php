<?php

/**
 * Description of Usuario
 *
 * @author link
 */
class Usuario {
    //put your code here
    private $id;
    private $nombre;
    private $apellidos;
    private $correo;
    private $password;
    private $tipo;
    private $telefono;
    private $imagen;
    private $fecha;
    
    // Constructor
    public function __construct($id, $nombre, $apellidos, $correo, $password, $tipo, $telefono, $imagen, $fecha) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->correo = $correo;
        $this->password = $password;
        $this->tipo = $tipo;
        $this->telefono = $telefono;
        $this->imagen = $imagen;
        $this->fecha = $fecha;
    }
    
    // **** GETS & SETS
    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    
    function getApellidos() {
        return $this->apellidos;
    }

    function getCorreo() {
        return $this->correo;
    }
    function getPassword() {
        return $this->password;
    }
    function getTipo() {
        return $this->tipo;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getImagen() {
        return $this->imagen;
    }
    
    function getFecha() {
        return $this->fecha;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApellidos($apellidos) {
        $this->apellidos = $apellidos;
    }
    
    function setCorreo($correo) {
        $this->correo = $correo;
    }
    
    function setPassword($password) {
        $this->password = $password;
    }

    function setTipo($tipo) {
        $this->tipo= $tipo;
    } 

    function setTelefono($telefono) {
        $this->telefono= $telefono;
    } 

    function setImagen($imagen) {
        $this->imagen= $imagen;
    } 

    function setFecha($fecha) {
        $this->fecha= $fecha;
    }
}

