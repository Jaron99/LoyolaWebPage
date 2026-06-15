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
}
