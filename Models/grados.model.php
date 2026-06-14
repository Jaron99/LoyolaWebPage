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

    public function obtenerAlumnosSinMatricula()
    {
        // La regla: Traer alumnos cuyo ID NO esté ya registrado en la tabla de matrícula.
        $sql = "SELECT id_alumno, nombres, apellidos, cod_mined 
                FROM alumno 
                WHERE id_alumno NOT IN (SELECT id_alumno FROM matricula)";

        $resultado = $this->conexion->query($sql);

        $alumnos = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $alumnos[] = $fila;
            }
        }
        return $alumnos;
    }

    public function matricularAlumno($id_alumno, $id_seccion)
    {
        $sql = "INSERT INTO matricula (id_alumno, id_seccion) VALUES (?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $id_alumno, $id_seccion);

        return $stmt->execute();
    }

    public function obtenerDocentes () {
        $sql = "SELECT id_profesor, CONCAT(nombre_prof, ' ', apellido_prof) AS nombre_completo FROM profesor";
        $resultado = $this->conexion->query($sql);

        $profesores = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $profesores[] = $fila;
            }
        }
        return $profesores;
    }

    public function asignarMaestroGuia($id_seccion, $id_profesor) {
        if (empty($id_profesor)) {
            $sql = "UPDATE seccion SET id_profesor = NULL WHERE id_seccion = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("i", $id_seccion); // "i" porque id_seccion es INT
        } else {
            $sql = "UPDATE seccion SET id_profesor = ? WHERE id_seccion = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("si", $id_profesor, $id_seccion); // "s" para VARCHAR (id_profesor), "i" para INT (id_seccion)
        }
        return $stmt->execute();
    }

    public function desmatricularAlumno($id_alumno, $id_seccion) {
        $sql = "DELETE FROM matricula WHERE id_alumno = ? AND id_seccion = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $id_alumno, $id_seccion);
        return $stmt->execute();
    }
    public function trasladarAlumno($id_alumno, $id_seccion_origen, $id_seccion_destino) {
        $sql = "UPDATE matricula SET id_seccion = ? WHERE id_alumno = ? AND id_seccion = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("iii", $id_seccion_destino, $id_alumno, $id_seccion_origen);
        return $stmt->execute();
    }
}
