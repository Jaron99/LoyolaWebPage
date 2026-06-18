<?php
require_once __DIR__ . "/../models/docentes.model.php";

$docentesModel = new Docentes();

// ==========================================
// 1. PROCESAR ACCIONES DE FORMULARIOS (POST)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // REGISTRAR DOCENTE
    if (isset($_POST['accion']) && $_POST['accion'] === 'registrar_docente') {
        $id = trim($_POST['id_profesor']);
        $nombre = trim($_POST['nombre_prof']);
        $apellido = trim($_POST['apellido_prof']);
        $especialidad = trim($_POST['especialidad']);
        $telefono = trim($_POST['telefono']);

        $docentesModel->registrarDocente($id, $nombre, $apellido, $especialidad, $telefono);
        
        // Lógica para el botón de "Guardar y Continuar"
        $boton_presionado = $_POST['btn_accion'] ?? 'guardar_y_cerrar';
        if ($boton_presionado === 'guardar_y_continuar') {
            header("Location: ../views/admin.view.php?tab=docentes&msg=creado_continuar");
        } else {
            header("Location: ../views/admin.view.php?tab=docentes&msg=creado");
        }
        exit();
    }

    // EDITAR DOCENTE
    if (isset($_POST['accion']) && $_POST['accion'] === 'editar_docente') {
        $id_profesor = trim($_POST['id_profesor']);
        $nombre = trim($_POST['nombre_prof']);
        $apellido = trim($_POST['apellido_prof']);
        $especialidad = trim($_POST['especialidad']);
        $telefono = trim($_POST['telefono']);

        $docentesModel->actualizarDocente($id_profesor, $nombre, $apellido, $especialidad, $telefono);
        header("Location: ../views/admin.view.php?tab=docentes&msg=actualizado");
        exit();
    }

    // ELIMINAR DOCENTE
    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_docente') {
        $id_profesor = trim($_POST['id_profesor']); 
        $docentesModel->eliminarDocente($id_profesor);
        header("Location: ../views/admin.view.php?tab=docentes&msg=eliminado");
        exit();
    }
}

$listaDocentes = [];

// Llamamos al modelo para traer la lista filtrada
$listaDocentes = $docentesModel->obtenerDocentesFiltrados();
?>