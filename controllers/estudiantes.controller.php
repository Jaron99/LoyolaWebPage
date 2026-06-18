<?php
require_once __DIR__ . "/../models/estudiantes.model.php";

$estudiantesModel = new Estudiantes();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
// A. REGISTRAR NUEVO ESTUDIANTE
    if (isset($_POST['accion']) && $_POST['accion'] === 'registrar_estudiante') {
        $nombres = trim($_POST['nombres']);
        $apellidos = trim($_POST['apellidos']);
        $telefono = trim($_POST['telefono']);
        $direccion = trim($_POST['direccion']);
        $fecha_nac = trim($_POST['fecha_nac']);
        $cod_mined = trim($_POST['cod_mined']);

        $estudiantesModel->registrarEstudiante($nombres, $apellidos, $telefono, $direccion, $fecha_nac, $cod_mined);
        
        // Verificamos qué botón presionó el usuario
        $boton_presionado = $_POST['btn_accion'] ?? 'guardar_y_cerrar';
        
        if ($boton_presionado === 'guardar_y_continuar') {
            header("Location: ../views/admin.view.php?tab=estudiantes&msg=creado_continuar");
        } else {
            header("Location: ../views/admin.view.php?tab=estudiantes&msg=creado");
        }
        exit();
    }

    // B. EDITAR ESTUDIANTE EXISTENTE
    if (isset($_POST['accion']) && $_POST['accion'] === 'editar_estudiante') {
        $id_alumno = (int) $_POST['id_alumno'];
        $nombres = trim($_POST['nombres']);
        $apellidos = trim($_POST['apellidos']);
        $telefono = trim($_POST['telefono']);
        $direccion = trim($_POST['direccion']);
        $fecha_nac = trim($_POST['fecha_nac']);
        $cod_mined = trim($_POST['cod_mined']);

        $estudiantesModel->actualizarEstudiante($id_alumno, $nombres, $apellidos, $telefono, $direccion, $fecha_nac, $cod_mined);
        
        header("Location: ../views/admin.view.php?tab=estudiantes&msg=actualizado");
        exit();
    }

    // C. ELIMINAR ESTUDIANTE
    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_estudiante') {
        $id_alumno = (int) $_POST['id_alumno'];
        
        $estudiantesModel->eliminarEstudiante($id_alumno);
        
        header("Location: ../views/admin.view.php?tab=estudiantes&msg=eliminado");
        exit();
    }
}

$listaEstudiantes = [];

// 1. Cargamos la lista de grados para el Combobox
$listaSecciones = $estudiantesModel->obtenerSeccionesComboBox();

$listaEstudiantes = $estudiantesModel->obtenerEstudiantesFiltrados();
?>