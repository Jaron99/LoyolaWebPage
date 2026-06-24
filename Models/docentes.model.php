<?php
require_once "conexion.model.php";

class Docentes
{
    private $conexion;
    // Constructor para establecer la conexión a la base de datos
    public function __construct()
    {
        $this->conexion = Conexion::connect();
    }
    // 1. LEER: Obtener docentes usando la VISTA SQL y aplicando filtro
    public function obtenerDocentesFiltrados($busqueda = "")
    {
        // Usamos la vista que creamos
        $sql = "SELECT * FROM vw_lista_docentes 
                WHERE nombre_prof LIKE ? OR apellido_prof LIKE ? OR especialidad LIKE ?
                ORDER BY apellido_prof ASC, nombre_prof ASC";

        $stmt = $this->conexion->prepare($sql);
        $busqueda_param = "%" . $busqueda . "%";
        $stmt->bind_param("sss", $busqueda_param, $busqueda_param, $busqueda_param);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $docentes = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $docentes[] = $fila;
            }
        }
        return $docentes;
    }

    // 2. CREAR: Registrar docente llamando al SP
    public function registrarDocente($id, $nombre, $apellido, $especialidad, $telefono)
    {
        $sql = "CALL sp_insertar_profesor(?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssss", $id, $nombre, $apellido, $especialidad, $telefono);
        return $stmt->execute();
    }

    public function actualizarDocente($id, $nombre, $apellido, $especialidad, $telefono)
    {
        $sql = "CALL sp_actualizar_profesor(?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssss", $id, $nombre, $apellido, $especialidad, $telefono);
        return $stmt->execute();
    }

    public function eliminarDocente($id)
    {
        $sql = "CALL sp_eliminar_profesor(?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $id);
        return $stmt->execute();
    }
// Función para obtener los KPIs del Dashboard del Docente
    public function obtenerEstadisticasDocente($id_profesor) {
        $estadisticas = [
            'total_alumnos' => 0,
            'total_asignaturas' => 0,
            'total_secciones' => 0
        ];

        // 1. Contar Asignaturas únicas que imparte el profesor
        $sql1 = "SELECT COUNT(id_asig) as asignaturas 
                 FROM asignatura 
                 WHERE id_profesor = ?";
        $stmt1 = $this->conexion->prepare($sql1);
        $stmt1->bind_param("s", $id_profesor); // Usamos "s" porque el ID es VARCHAR (Ej. DOC-001)
        $stmt1->execute();
        $resultado1 = $stmt1->get_result()->fetch_assoc();
        
        if ($resultado1) {
            $estadisticas['total_asignaturas'] = $resultado1['asignaturas'];
        }

        // 2. Contar Secciones y Alumnos cruzando Asignatura -> Grado -> Sección -> Matrícula
        $sql2 = "SELECT 
                    COUNT(DISTINCT s.id_seccion) as secciones,
                    COUNT(DISTINCT m.id_alumno) as alumnos
                 FROM asignatura a
                 JOIN seccion s ON a.id_grado = s.id_grado
                 LEFT JOIN matricula m ON s.id_seccion = m.id_seccion
                 WHERE a.id_profesor = ?";
                 
        $stmt2 = $this->conexion->prepare($sql2);
        $stmt2->bind_param("s", $id_profesor);
        $stmt2->execute();
        $resultado2 = $stmt2->get_result()->fetch_assoc();
        
        if ($resultado2) {
            $estadisticas['total_secciones'] = $resultado2['secciones'];
            $estadisticas['total_alumnos'] = $resultado2['alumnos'];
        }

        return $estadisticas;
    }
}
