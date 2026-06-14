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

    //Funcion para obtener todos los usuarios 
   //Funcion para obtener todos los usuarios 
    public function getUsuarios($filtrorol = "", $busquedaUsuarios = "")
    {
        $sql = "SELECT * FROM usuarios WHERE 1=1";
        // Si el usuario selecciona un rol en el combo box
        if (!empty($filtrorol)) {
            $rolSeguro = $this->conexion->real_escape_string($filtrorol);
            $sql .= " AND rol = '$rolSeguro'";
        }

        // Si el usuario escribe algo en el buscador
        if (!empty($busquedaUsuarios)) {
            $busquedaSegura = $this->conexion->real_escape_string($busquedaUsuarios);
            $sql .= " AND usuario LIKE '%$busquedaSegura%'";
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
        //si ocurre un error, puedes manejarlo aquí
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
    public function obtenerGradosSeccion($filtronivel = "", $busqueda = "")
    {
        $sql = "SELECT * FROM vw_grados_secciones where 1=1"; // Empezamos con una condición siempre verdadera para facilitar la concatenación

        // Si el usuario selecciono una modalidad en el combo box
        if (!empty($filtronivel)) {
            $nivelseguro = $this->conexion->real_escape_string($filtronivel);
            $sql .= " AND modalidad = '$nivelseguro'";
        }

        // Si el usuario escribe algo en el buscador
        if (!empty($busqueda)) {
            $busquedasegura = $this->conexion->real_escape_string($busqueda);
            $sql .= " AND (nombre_grad LIKE '%$busquedasegura%' OR 
                        nombre_sec LIKE '%$busquedasegura%' OR 
                        modalidad LIKE '%$busquedasegura%')";
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


    //CRUD de Alumnos y Profesores
    public function crearAlumno()
    {
        // Aquí iría la lógica para crear un alumno
    }
    public function editarAlumno()
    {
        // Aquí iría la lógica para editar un alumno
    }

    public function eliminarAlumno()
    {
        // Aquí iría la lógica para eliminar un alumno
    }
    public function crearProfesor()
    {
        // Aquí iría la lógica para crear un profesor
    }
    public function editarProfesor()
    {
        // Aquí iría la lógica para editar un profesor
    }
    public function eliminarProfesor()
    {
        // Aquí iría la lógica para eliminar un profesor
    }

    //CRUD de Grados y Secciones
    public function crearGradoSeccion()
    {
        // Aquí iría la lógica para crear un grado y sección
    }
    public function editarGradoSeccion()
    {
        // Aquí iría la lógica para editar un grado y sección
    }
    public function eliminarGradoSeccion()
    {
        // Aquí iría la lógica para eliminar un grado y sección
    }

}
