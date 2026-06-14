<?php

include_once "conexion.model.php";

class Usuarios{
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::connect();
    }

    public function login ($usuario) {
        $usuarioSeguro = $this->conexion->real_escape_string($usuario);

        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuarioSeguro' AND estado = '1'";
        $result = $this->conexion->query($sql);
        
        return $result;
    }

        public function cambiarcontrasena($usuario, $nuevacontrasena)
    {
        $userSeguro = $this->conexion->real_escape_string($usuario);
        $passHash = password_hash($nuevacontrasena, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios SET contrasena = '$passHash' WHERE usuario = '$userSeguro'";

        if ($this->conexion->query($sql)) {
            return true; // Contraseña actualizada exitosamente
        } else {
            return false; // Error al actualizar la contraseña
        }
    }

        public function bloquearUsuario($usuario, $nuevoestado)
    {
        $userSeguro = $this->conexion->real_escape_string($usuario);
        $estadoSeguro = ($nuevoestado == '1') ? '1' : '0'; 
        $sql = "UPDATE usuarios SET estado = '$estadoSeguro' WHERE usuario = '$userSeguro'";
        return $this->conexion->query($sql);
    }

        public function eliminarUsuario($usuario)
    {
        $usuarioSeguro = $this->conexion->real_escape_string($usuario);
        $sql = "DELETE FROM usuarios WHERE usuario = '$usuarioSeguro'";
        return $this->conexion->query($sql);
    }

        public function crearUsuario($usuario, $contrasena, $rol)
    {
        $userSeguro = $this->conexion->real_escape_string($usuario);
        $passHash = password_hash($contrasena, PASSWORD_DEFAULT);
        $rolSeguro = $this->conexion->real_escape_string($rol);

        $sql = "INSERT INTO usuarios (usuario, contrasena, rol, estado) VALUES ('$userSeguro', '$passHash', '$rolSeguro', '1')";
        return $this->conexion->query($sql);
    }
}

