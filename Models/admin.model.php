<?php

include_once "conexion.model.php";

class Admin {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::connect();
    }

    //Funcion para obtener todos los usuarios 
    public function getUsuarios() {
        $sql = "SELECT * FROM usuarios";
        $result = $this->conexion->query($sql);
        
        return $result;
    }

    
}