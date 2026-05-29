<?php

class Conexion {

    public static function connect() {
        $conn = new mysqli("localhost", "root", "1234", "sistema_cpsil");

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $conn->set_charset("utf8");
        return $conn;
    }
}

?>