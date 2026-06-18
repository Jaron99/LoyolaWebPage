<?php
require_once "conexion.model.php";

class Evaluacion{

    private $conexion;
    public function __construct() {
        $this->conexion = Conexion::connect();
    }

    // Buscar alumnos de una sección específica y traer sus notas si ya existen
    public function obtenerAlumnosParaEvaluar($id_seccion, $id_asig) {
        $sql = "SELECT m.id_matricula, a.nombres, a.apellidos, e. parcial, e.nota 
                FROM matricula m
                JOIN alumno a ON m.id_alumno = a.id_alumno
                LEFT JOIN evaluacion e ON m.id_matricula = e.id_matricula 
                     AND e.id_asig = ?
                WHERE m.id_seccion = ?
                ORDER BY a.apellidos ASC, a.nombres ASC";
                
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $id_asig, $id_seccion);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Guardar o actualizar la nota
public function guardarNota($id_matricula, $id_asig, $nota, $parcial) {
        // 1. Verificamos si la nota ya existe en la base de datos
        $sql_check = "SELECT nota FROM evaluacion WHERE id_matricula = ? AND id_asig = ? AND parcial = ?";
        $stmt_check = $this->conexion->prepare($sql_check);
        $stmt_check->bind_param("iss", $id_matricula, $id_asig, $parcial);
        $stmt_check->execute();
        $resultado = $stmt_check->get_result();

        if ($resultado->num_rows > 0) {
            // 2. Si ya existe, hacemos un UPDATE
            $sql_upd = "UPDATE evaluacion SET nota = ?, fecha_eval = CURDATE() 
                        WHERE id_matricula = ? AND id_asig = ? AND parcial = ?";
            $stmt_upd = $this->conexion->prepare($sql_upd);
            $stmt_upd->bind_param("siss", $nota, $id_matricula, $id_asig, $parcial);
            return $stmt_upd->execute();
        } else {
            // 3. Si no existe, hacemos un INSERT
            $sql_ins = "INSERT INTO evaluacion (id_matricula, id_asig, nota, parcial, fecha_eval)
                        VALUES (?, ?, ?, ?, CURDATE())";
            $stmt_ins = $this->conexion->prepare($sql_ins);
            $stmt_ins->bind_param("isss", $id_matricula, $id_asig, $nota, $parcial);
            return $stmt_ins->execute();
        }
    }
}
?>