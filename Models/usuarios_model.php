<?php

include_once "conexion.model.php";

class Usuarios{
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::connect();
    }

    public function login ($usuario, $contrasena) {
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND contrasena = '$contrasena' AND estado = '1'";
        $result = $this->conexion->query($sql);
        
        return $result;
    }
}

