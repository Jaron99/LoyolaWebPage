<?php
include_once "conexion.model.php";

class Grados
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::connect();
    }

    public function obtenerAlumnosPorSeccion($idSeccion)
    {
        $sql = "SELECT *
            FROM vw_lista_alumnos
            WHERE id_seccion = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idSeccion);
        $stmt->execute();

        return $stmt->get_result();
    }
}
