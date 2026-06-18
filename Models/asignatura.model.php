<?php
require_once "conexion.model.php";

class Asignatura {

    private $conexion;
    public function __construct() {
        $this->conexion = Conexion::connect();
    }

    public function obtenerAsignaturasPorRol($rol, $id_profesor = null) {
        if ($rol === 'admin') {
            // El admin ve todas las asignaturas y quién las imparte
            $sql = "SELECT a.id_asig, a.nombre_asig, g.id_grado, g.nombre_grad, p.nombre_prof, p.apellido_prof
                    FROM asignatura a
                    INNER JOIN grado g ON a.id_grado = g.id_grado
                    LEFT JOIN profesor p ON a.id_profesor = p.id_profesor
                    ORDER BY g.id_grado ASC";
            $stmt = $this->conexion->prepare($sql);
        } else {
            // El docente solo ve las suyas
            $sql = "SELECT a.id_asig, a.nombre_asig, g.id_grado, g.nombre_grad
                    FROM asignatura a
                    INNER JOIN grado g ON a.id_grado = g.id_grado
                    WHERE a.id_profesor = ?
                    ORDER BY g.id_grado ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("s", $id_profesor);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();
        
        $asignaturas = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $asignaturas[] = $fila;
            }
        }
        return $asignaturas;
    }
    public function obtenerSeccionesPorGrado($id_grado) {
        $sql = "SELECT id_seccion, nombre_sec FROM seccion WHERE id_grado = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_grado);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>