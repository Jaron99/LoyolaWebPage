<?php
require_once "conexion.model.php";

class Configuracion {

    private $conexion;
    public function __construct() {
        $this->conexion = Conexion::connect();
    }

   public function obtenerAjustes() {
        $sql = "SELECT nombre_ajuste, valor FROM configuracion";
        $resultado = $this->conexion->query($sql);
        $ajustes = [];
        
        while ($fila = $resultado->fetch_assoc()) {
            if ($fila['valor'] === 'true') {
                $ajustes[$fila['nombre_ajuste']] = true;
            } elseif ($fila['valor'] === 'false') {
                $ajustes[$fila['nombre_ajuste']] = false;
            } else {
                $ajustes[$fila['nombre_ajuste']] = $fila['valor']; // Para el año lectivo
            }
        }
        return $ajustes;
    }

    public function actualizarAjustes($ajustes) {
        $sql = "UPDATE configuracion SET valor = ? WHERE nombre_ajuste = ?";
        $stmt = $this->conexion->prepare($sql);
        $exito = true;
        
        foreach ($ajustes as $nombre => $valor) {
            if ($valor === true) { $str_valor = 'true'; }
            elseif ($valor === false) { $str_valor = 'false'; }
            else { $str_valor = (string)$valor; }

            $stmt->bind_param("ss", $str_valor, $nombre);
            if (!$stmt->execute()) { $exito = false; }
        }
        return $exito;
    }
}
?>