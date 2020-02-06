<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Luchador
 *
 * @author link
 */
class Producto {
    //put your code here
    private $id;
    private $nombre;
    private $tipo;
    private $marca;
    private $precio;
    private $unidades;
    private $imagen;


    
    // Constructor
    public function __construct($id, $nombre, $tipo, $marca, $precio, $unidades, $imagen) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->marca = $marca;
        $this->precio = $precio;
        $this->unidades = $unidades;
        $this->imagen = $imagen;
    }
    
    // **** GETS & SETS
    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }
    
    function getTipo() {
        return $this->tipo;
    }

    function getMarca() {
        return $this->marca;
    }

    function getPrecio() {
        return $this->precio;
    }

    function getUnidades() {
        return $this->unidades;
    }

    function getImagen() {
        return $this->imagen;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }
    
    function setMarca($marca) {
        $this->marca = $marca;
    } 

    function setPrecio($precio) {
        $this->precio= $precio;
    }
    
    function setUnidades($unidades) {
        $this->unidades= $unidades;
    }

    function setImagen($imagen) {
        $this->imagen= $imagen;
    } 
}

