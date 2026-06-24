<?php
include_once "conexion.model.php";

class Usuarios {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::connect();
    }

    public function login($usuario) {
        $sql = "SELECT * FROM usuarios WHERE usuario = ? AND estado = 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function cambiarcontrasena($usuario, $nuevacontrasena) {
        // ✅ Siempre hashear con bcrypt
        $passHash = password_hash($nuevacontrasena, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET contrasena = ? WHERE usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $passHash, $usuario);
        return $stmt->execute();
    }

    public function bloquearUsuario($usuario, $nuevoestado) {
        // ✅ Forzamos que solo sea 0 o 1, nunca un valor arbitrario
        $estadoSeguro = ($nuevoestado == '1') ? 1 : 0;
        $sql = "UPDATE usuarios SET estado = ? WHERE usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("is", $estadoSeguro, $usuario);
        return $stmt->execute();
    }

    public function eliminarUsuario($usuario) {
        $sql = "DELETE FROM usuarios WHERE usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $usuario);
        return $stmt->execute();
    }

    public function crearUsuario($usuario, $contrasena, $rol) {
        $rolesPermitidos = ['admin', 'docente', 'estudiante'];
        if (!in_array($rol, $rolesPermitidos)) {
            return false;
        }

        $passHash = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (usuario, contrasena, rol, estado) VALUES (?, ?, ?, 1)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sss", $usuario, $passHash, $rol);
        return $stmt->execute();
    }

    public function cambiarNombreUsuario($usuario, $nuevousuario) {
        $sql = "UPDATE usuarios SET usuario = ? WHERE usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $nuevousuario, $usuario);
        return $stmt->execute();
    }
}