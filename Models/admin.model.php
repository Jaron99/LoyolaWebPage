<?php

include_once "conexion.model.php";

class Admin
{
    private $conexion;
    // Constructor para establecer la conexión a la base de datos
    public function __construct()
    {
        $this->conexion = Conexion::connect();
    }


    public function getUsuarios()
    {
        $sql = "SELECT * FROM usuarios WHERE 1=1";

        $result = $this->conexion->query($sql);
        $lista = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $lista[] = $row; 
            }
        }
        return $lista;
    }

    // Función para obtener los roles de los usuarios
    public function obtenerRolesUsuarios()
    {
        $sql = "SELECT DISTINCT rol FROM usuarios WHERE rol IS NOT NULL";
        $result = $this->conexion->query($sql);

        $roles = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $roles[] = $row['rol'];
            }
        }
        return $roles;
    }

    // Función para obtener el resumen del dashboard
    public function obtenerResumenDashboard()
    {
        $sql = "CALL sp_obtener_resumen_dashboard()";
        $result = $this->conexion->query($sql);

        if ($result) {
            $resumen = $result->fetch_assoc();
            $result->free();

            while ($this->conexion->more_results() && $this->conexion->next_result()) {
                if ($extraResult = $this->conexion->store_result()) {
                    $extraResult->free();
                }
            }
            return $resumen;
        }
        return ['total_alumnos' => 0, 'total_profesores' => 0, 'periodo_actual' => 'N/A'];
    }

    // Función para obtener los niveles académicos únicos (modalidades)
    public function obtenerNivelesAcademicos()
    {
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

    // Función para obtener grados y secciones con filtros 
    public function obtenerGradosSeccion()
    {
        $sql = "SELECT * FROM vw_grados_secciones where 1=1"; // Empezamos con una condición siempre verdadera para facilitar la concatenación

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
