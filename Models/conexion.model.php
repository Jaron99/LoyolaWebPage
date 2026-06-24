<?php
require_once __DIR__ . "/../.env.php";

class Conexion {
    public static function connect() {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($conn->connect_error) {
            error_log("Error de conexión: " . $conn->connect_error);
            die("Error interno del servidor. Contacte al administrador.");
        }

        $conn->set_charset("utf8mb4"); 
        return $conn;
    }
}