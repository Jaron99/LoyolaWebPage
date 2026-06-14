<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../views/login.view.php");
    exit();
}

include_once __DIR__ . "/../models/admin.model.php";
$adminModel = new Admin();

// Obtenemos los datos globales para el dashboard y los combos
$resumenDashboard = $adminModel->obtenerResumenDashboard();
$nivelesAcademicos = $adminModel->obtenerNivelesAcademicos();
$listaRoles = $adminModel->obtenerRolesUsuarios();

$active = isset($_GET['tab']) ? $_GET['tab'] : 'panel';

// FILTROS PARA GRADOS Y SECCIONES
$filtronivel = isset($_GET['nivel']) ? $_GET['nivel'] : "";
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : "";
$listaGrados = $adminModel->obtenerGradosSeccion($filtronivel, $busqueda);

// FILTROS PARA USUARIOS
$filtrorol = isset($_GET['rol']) ? $_GET['rol'] : "";
$busquedaUsuarios = isset($_GET['busquedaUsuarios']) ? $_GET['busquedaUsuarios'] : "";
$usuarios = $adminModel->getUsuarios($filtrorol, $busquedaUsuarios); // Trae los usuarios filtrados

require_once "../models/grados.model.php";

$gradosModel = new Grados();

$alumnosMatriculados = [];
$mostrarModalAlumnos = false;
$alumnosSinMatricula = [];
$mostrarModalMatricula = false;
$idSeccionMatricula = 0;
$nombreGradoMatricula = "";
$mostrarModalEditarGrado = false;
$datosGradoEditar = [];
$alumnosDelGradoEditar = [];
$listaDocentes = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {

    if ($_POST['accion'] === 'ver_alumnos') {

        $idSeccion = (int) $_POST['id_seccion'];

        $resultado = $gradosModel->obtenerAlumnosPorSeccion($idSeccion);

        while ($fila = $resultado->fetch_assoc()) {
            $alumnosMatriculados[] = $fila;
        }

        $mostrarModalAlumnos = true;
        $active = "grados";
    }
    // Interceptamos el botón de Matricular
    if ($_POST['accion'] === 'preparar_matricula') {
        $idSeccionMatricula = (int) $_POST['id_seccion'];
        $nombreGradoMatricula = $_POST['nombre_grado'];

        // Llamamos a la función restrictiva
        $alumnosSinMatricula = $gradosModel->obtenerAlumnosSinMatricula();

        $mostrarModalMatricula = true;
        $active = "grados";
    }

    if ($_POST['accion'] === 'procesar_matricula') {
        $idSeccion = (int) $_POST['id_seccion'];

        // Recibimos el array de checkboxes (si no marcaron ninguno, queda un array vacío)
        $alumnosSeleccionados = isset($_POST['alumnos_seleccionados']) ? $_POST['alumnos_seleccionados'] : [];

        // Si hay alumnos seleccionados, los guardamos uno por uno
        if (!empty($alumnosSeleccionados)) {
            foreach ($alumnosSeleccionados as $id_alumno) {
                // Llamamos a la función del modelo por cada alumno marcado
                $gradosModel->matricularAlumno((int) $id_alumno, $idSeccion);
            }
        }

        // Redirigimos de vuelta a la pestaña para limpiar el formulario y evitar que se dupliquen al recargar la página
        header("Location: admin.view.php?tab=grados");
        exit();
    }
   if ($_POST['accion'] === 'preparar_editar_grado') {
        $idSeccion = (int) $_POST['id_seccion'];
        
        $datosGradoEditar = [
            'id_seccion' => $idSeccion,
            'grado_nombre' => $_POST['grado_nombre'],
            'seccion_nombre' => $_POST['seccion_nombre'],
            'id_profesor_actual' => $_POST['id_profesor_actual'] ?? ''
        ];

        // 1. Cargamos los profesores para la primera pestaña
        $listaProfesores = $gradosModel->obtenerDocentes();

        // 2. LA SOLUCIÓN A LA LISTA VACÍA: Cargamos a los alumnos actuales de esta sección
        $alumnosDelGradoEditar = [];
        $resultadoAlumnos = $gradosModel->obtenerAlumnosPorSeccion($idSeccion);
        if ($resultadoAlumnos) {
            while ($fila = $resultadoAlumnos->fetch_assoc()) {
                $alumnosDelGradoEditar[] = $fila;
            }
        }
        
        $mostrarModalEditarGrado = true;
        $active = "grados";
    }
    
    if ($_POST['accion'] === 'actualizar_maestro_guia') {
        $idSeccion = (int) $_POST['id_seccion'];
        $idProfesor = $_POST['id_profesor']; // Recibimos el código "DOC-00X"
        
        $gradosModel->asignarMaestroGuia($idSeccion, $idProfesor);
        
        header("Location: admin.view.php?tab=grados");
        exit();
    }

    // Acción: Retirar alumnos de la sección actual (quitar matrícula)
    if ($_POST['accion'] === 'retirar_alumnos_seccion') {
        $idSeccion = (int) $_POST['id_seccion_origen'];
        $alumnosARetirar = isset($_POST['alumnos_retiro']) ? $_POST['alumnos_retiro'] : [];

        if (!empty($alumnosARetirar)) {
            foreach ($alumnosARetirar as $id_alumno) {
                // Los desmatriculamos uno a uno
                $gradosModel->desmatricularAlumno((int)$id_alumno, $idSeccion);
            }
        }
        header("Location: admin.view.php?tab=grados");
        exit();
    }

    if ($_POST['accion'] === 'trasladar_alumnos') {
        $idSeccionOrigen = (int) $_POST['id_seccion_origen'];
        $idSeccionDestino = (int) $_POST['id_seccion_destino'];
        $alumnosATrasladar = isset($_POST['alumnos_traslado']) ? $_POST['alumnos_traslado'] : [];

        // Aseguramos que hayan marcado alumnos y seleccionado un destino válido
        if (!empty($alumnosATrasladar) && $idSeccionDestino > 0) {
            foreach ($alumnosATrasladar as $id_alumno) {
                $gradosModel->trasladarAlumno((int)$id_alumno, $idSeccionOrigen, $idSeccionDestino);
            }
        }
        header("Location: admin.view.php?tab=grados");
        exit();
    }

    if ($_POST['accion'] === 'eliminar_grado') {
        $idSeccion = (int) $_POST['id_seccion'];
        
        // Llamamos a la función doble del modelo
        $gradosModel->eliminarSeccion($idSeccion);
        
        // Refrescamos la vista
        header("Location: admin.view.php?tab=grados");
        exit();
    }

    if ($_POST['accion'] === 'guardar_nuevo_grado') {
        $idGrado = (int) $_POST['id_grado'];
        $nombreSec = strtoupper(trim($_POST['nombre_sec'])); 

        // Evaluamos usando la función del modelo
        if ($gradosModel->existeSeccion($idGrado, $nombreSec)) {
            // ¡Ya existe! Guardamos un mensaje de error en la sesión para mostrarlo en la vista
            session_start();
            $_SESSION['error_grado'] = "¡Error! Esa sección ya se encuentra registrada en el grado seleccionado.";
        } else {
            $gradosModel->registrarSeccion($idGrado, $nombreSec);
        }

        header("Location: admin.view.php?tab=grados");
        exit();
    }
}
