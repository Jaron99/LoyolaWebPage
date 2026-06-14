<?php
require_once "conexion.model.php";

class Estudiantes
{

    private $conexion;
    public function __construct()
    {
        $this->conexion = Conexion::connect();
    }

    public function obtenerEstudiantes($nombres_buscar = "")
    {
        $sql = "CALL sp_buscar_alumno(?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombres_buscar);
        $stmt->execute();

        $resultado = $stmt->get_result();

        $estudiantes = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $estudiantes[] = $fila;
            }
            while ($this->conexion->next_result()) {
                $this->conexion->use_result();
            }
        }
        return $estudiantes;
    }

    public function registrarEstudiante($nombres, $apellidos, $telefono, $direccion, $fecha_nac, $cod_mined)
    {
        // Ahora el procedimiento recibe 6 parámetros
        $sql = "CALL sp_crear_alumno(?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        // "ssssss" -> 6 datos de tipo string/texto
        $stmt->bind_param("ssssss", $nombres, $apellidos, $telefono, $direccion, $fecha_nac, $cod_mined);
        return $stmt->execute();
    }

    public function actualizarEstudiante($id_alumno, $nombres, $apellidos, $telefono, $direccion, $fecha_nac, $cod_mined) {
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

    // 1. Obtener la lista de Grados/Secciones para el Combobox
    public function obtenerSeccionesComboBox() {
        $sql = "SELECT s.id_seccion, CONCAT(g.nombre_grad, ' ', s.nombre_sec) AS nombre_completo 
                FROM seccion s 
                INNER JOIN grado g ON s.id_grado = g.id_grado 
                ORDER BY g.id_grado, s.nombre_sec";
        $resultado = $this->conexion->query($sql);
        
        $secciones = [];
        if ($resultado) {
            while($fila = $resultado->fetch_assoc()){
                $secciones[] = $fila;
            }
        }
        return $secciones;
    }

    // 2. Leer de la vista SQL filtrando por nombre y/o por sección
    public function obtenerEstudiantesFiltrados($busqueda = "", $id_seccion = "") {
        $busqueda_param = "%" . $busqueda . "%";
        
        if ($id_seccion !== "") {
            // Si el usuario escogió un grado en el combobox
            $sql = "SELECT * FROM vw_lista_alumnos 
                    WHERE (nombres LIKE ? OR apellidos LIKE ?) AND id_seccion = ? 
                    ORDER BY apellidos ASC, nombres ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("ssi", $busqueda_param, $busqueda_param, $id_seccion);
        } else {
            // Si el combobox está en "Todos los grados"
            $sql = "SELECT * FROM vw_lista_alumnos
                    WHERE (nombres LIKE ? OR apellidos LIKE ?) 
                    ORDER BY apellidos ASC, nombres ASC";
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
}
