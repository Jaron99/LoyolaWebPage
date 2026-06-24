<?php
require_once __DIR__ . "/conexion.model.php";

class Estudiantes
{
    private $conexion;
    public function __construct()
    {
        $this->conexion = Conexion::connect();
    }

    public function registrarEstudiante($nombres, $apellidos, $telefono, $direccion, $fecha_nac, $cod_mined)
    {
        $sql = "CALL sp_crear_alumno(?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssss", $nombres, $apellidos, $telefono, $direccion, $fecha_nac, $cod_mined);
        return $stmt->execute();
    }

    public function actualizarEstudiante($id_alumno, $nombres, $apellidos, $telefono, $direccion, $fecha_nac, $cod_mined)
    {
        $sql = "CALL sp_actualizar_alumno(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("issssss", $id_alumno, $nombres, $apellidos, $telefono, $direccion, $fecha_nac, $cod_mined);
        return $stmt->execute();
    }

    public function eliminarEstudiante($id_alumno)
    {
        $sql = "CALL sp_eliminar_alumno(?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_alumno);
        return $stmt->execute();
    }

    public function obtenerSeccionesComboBox()
    {
        $sql = "SELECT s.id_seccion, CONCAT(g.nombre_grad, ' ', s.nombre_sec) AS nombre_completo 
                FROM seccion s 
                INNER JOIN grado g ON s.id_grado = g.id_grado 
                ORDER BY g.id_grado, s.nombre_sec";
        $resultado = $this->conexion->query($sql);

        $secciones = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $secciones[] = $fila;
            }
        }
        return $secciones;
    }

    public function obtenerEstudiantesFiltrados($busqueda = "", $id_seccion = "")
    {
        $busqueda_param = "%" . $busqueda . "%";

        if ($id_seccion !== "") {
            $sql = "SELECT * FROM vw_lista_alumnos 
                    WHERE (nombres LIKE ? OR apellidos LIKE ?) AND id_seccion = ? 
                    ORDER BY apellidos ASC, nombres ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("ssi", $busqueda_param, $busqueda_param, $id_seccion);
        } else {
            $sql = "SELECT * FROM vw_lista_alumnos
                    WHERE (nombres LIKE ? OR apellidos LIKE ?) 
                    ORDER BY id_seccion ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("ss", $busqueda_param, $busqueda_param);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();

        $estudiantes = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $estudiantes[] = $fila;
            }
        }
        return $estudiantes;
    }

    public function obtenerPerfilEstudiante($id_alumno)
    {
        $sql = "SELECT a.*, g.nombre_grad, s.nombre_sec 
                FROM alumno a
                INNER JOIN matricula m ON a.id_alumno = m.id_alumno
                INNER JOIN seccion s ON m.id_seccion = s.id_seccion
                INNER JOIN grado g ON s.id_grado = g.id_grado
                WHERE a.id_alumno = ? LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_alumno);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado ? $resultado->fetch_assoc() : null;
    }

    public function obtenerNotasEstudiante($id_alumno)
    {
        $sql = "SELECT 
                    asig.nombre_asig,
                    MAX(CASE WHEN e.parcial = 'I Parcial' THEN e.nota END) as corte1,
                    MAX(CASE WHEN e.parcial = 'II Parcial' THEN e.nota END) as corte2,
                    MAX(CASE WHEN e.parcial = 'III Parcial' THEN e.nota END) as corte3,
                    MAX(CASE WHEN e.parcial = 'IV Parcial' THEN e.nota END) as corte4,
                    ROUND(AVG(e.nota), 2) as promedio,
                    CASE 
                        WHEN COUNT(e.nota) = 0 THEN 'En Curso'
                        WHEN AVG(e.nota) >= 60 THEN 'Aprobado'
                        ELSE 'Reprobado'
                    END as estado
                FROM matricula m
                INNER JOIN seccion s ON m.id_seccion = s.id_seccion
                INNER JOIN asignatura asig ON s.id_grado = asig.id_grado
                -- Conectamos usando tus campos reales: id_asig y id_matricula
                LEFT JOIN evaluacion e ON asig.id_asig = e.id_asig AND m.id_matricula = e.id_matricula
                WHERE m.id_alumno = ?
                GROUP BY asig.id_asig, asig.nombre_asig";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_alumno);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function ObtenerAnoLectivo() {
        $sql = "SELECT * FROM `periodo_academico` WHERE estado = 'Activo';";

        $resultado = $this->conexion->query($sql);
    
    if ($resultado && $resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        return $fila['anio']; // Retorna solo el nombre del año (ej: "2026")
    }
    
    return date('Y');
    } 
}
