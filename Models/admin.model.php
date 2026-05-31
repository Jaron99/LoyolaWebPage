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

    public function obtenerResumenDashboard() {
        $sql = "CALL sp_obtener_resumen_dashboard()";
        $result = $this->conexion->query($sql);

        if ($result) {
            $resumen = $result->fetch_assoc();
            $result->free();
            
            while ($this->conexion->more_results() && $this->conexion->next_result()){
                if ($extraResult = $this->conexion->store_result()) {
                    $extraResult->free();
                }
            }
            return $resumen;
        }
        //si ocurre un error, puedes manejarlo aquí
        return ['total_alumnos' => 0, 'total_profesores' => 0, 'periodo_actual' => 'N/A'];
    }

    public function obtenerNivelesAcademicos() {
        $sql = "SELECT DISTINCT modalidad FROM grado where modalidad IS NOT NULL";
        $result = $this->conexion->query($sql);

        $niveles = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $niveles[] = $row['modalidad'];
            }
        }
        return $niveles;
    }

    public function obtenerGradosSeccion ($filtronivel="") {
        $sql = "SELECT * FROM vw_grados_secciones";

        if (!empty($filtronivel)) {
            $nivelseguro = $this->conexion->real_escape_string($filtronivel);
            $sql .= " WHERE modalidad = '$nivelseguro'";
        }

        $result = $this->conexion->query($sql);

        $lista = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $lista[] = $row;
            }
        }
        return $lista;
    }
}