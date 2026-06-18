<?php
require_once "conexion.model.php";

class Respaldo
{

    private $conexion;
    public function __construct()
    {
        $this->conexion = Conexion::connect();
    }

    // 1. GENERAR RESPALDO (El que hicimos antes)
    public function generarRespaldoSQL()
    {
        $tablas = [];
        $vistas = [];

        // SHOW FULL TABLES diferencia entre 'BASE TABLE' y 'VIEW'
        $resultado = $this->conexion->query("SHOW FULL TABLES");
        while ($fila = $resultado->fetch_row()) {
            if ($fila[1] == 'VIEW') {
                $vistas[] = $fila[0];
            } else {
                $tablas[] = $fila[0];
            }
        }

        $sql_dump = "SET FOREIGN_KEY_CHECKS=0;\n\n";

        // --------------------------------------------------
        // 1. PROCESAR TABLAS REALES (Estructura y Datos)
        // --------------------------------------------------
        foreach ($tablas as $tabla) {
            // Borramos la tabla si existe para poder sobreescribirla
            $sql_dump .= "DROP TABLE IF EXISTS `$tabla`;\n";
            $sql_dump .= "DROP VIEW IF EXISTS `$tabla`;\n"; // Por precaución

            $row2 = $this->conexion->query("SHOW CREATE TABLE `$tabla`")->fetch_row();
            $sql_dump .= $row2[1] . ";\n\n";

            // Extraemos los datos de la tabla
            $resultado = $this->conexion->query("SELECT * FROM `$tabla`");
            $num_campos = $resultado->field_count;

            while ($fila = $resultado->fetch_row()) {
                $sql_dump .= "INSERT INTO `$tabla` VALUES(";
                for ($j = 0; $j < $num_campos; $j++) {
                    if (is_null($fila[$j])) {
                        $sql_dump .= "NULL";
                    } else {
                        $valor = $this->conexion->real_escape_string($fila[$j]);
                        $sql_dump .= "'" . $valor . "'";
                    }
                    if ($j < ($num_campos - 1)) {
                        $sql_dump .= ",";
                    }
                }
                $sql_dump .= ");\n";
            }
            $sql_dump .= "\n\n";
        }

        // --------------------------------------------------
        // 2. PROCESAR VISTAS (Solo Estructura, SIN datos)
        // --------------------------------------------------
        foreach ($vistas as $vista) {
            $sql_dump .= "DROP VIEW IF EXISTS `$vista`;\n";
            $sql_dump .= "DROP TABLE IF EXISTS `$vista`;\n"; // Por precaución

            $row2 = $this->conexion->query("SHOW CREATE VIEW `$vista`")->fetch_row();

            // Limpiamos el 'DEFINER' (Evita errores al cambiar de servidor Local a Hosting web)
            $creacion_vista = preg_replace('/DEFINER=`(.*?)`@`(.*?)`/', '', $row2[1]);

            $sql_dump .= $creacion_vista . ";\n\n";
        }

        $sql_dump .= "SET FOREIGN_KEY_CHECKS=1;\n";
        return $sql_dump;
    }
    public function restaurarRespaldo($sql_content)
    {
        $this->conexion->query("SET FOREIGN_KEY_CHECKS=0");

        // multi_query permite ejecutar múltiples comandos separados por punto y coma (;)
        $exito = $this->conexion->multi_query($sql_content);

        // Limpiamos los resultados de la memoria para evitar errores
        if ($exito) {
            do {
                if ($res = $this->conexion->store_result()) {
                    $res->free();
                }
            } while ($this->conexion->more_results() && $this->conexion->next_result());
        }

        $this->conexion->query("SET FOREIGN_KEY_CHECKS=1");
        return $exito;
    }

    public function registrarAccion($nombre_archivo, $tipo)
    {
        $sql = "INSERT INTO historial_respaldos (nombre_archivo, tipo_accion) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $nombre_archivo, $tipo);
        return $stmt->execute();
    }

    public function obtenerHistorial()
    {
        $sql = "SELECT * FROM historial_respaldos ORDER BY fecha_accion DESC LIMIT 5";
        $resultado = $this->conexion->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}
