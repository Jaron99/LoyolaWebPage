<?php
require_once __DIR__ . "/../models/docentes.model.php";

$docentesModel = new Docentes();

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
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    $listaDocentes = $docentesModel->obtenerDocentesFiltrados();
}

// 2. SI ES DOCENTE: Cargamos sus estadísticas del Dashboard
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'docente') {
    // El ID real del profesor viene de login.controller.php
    $id_del_profesor = $_SESSION['id_referencia'] ?? 0; 

    // Llamamos al modelo para obtener los números reales
    $estadisticas_docente = $docentesModel->obtenerEstadisticasDocente($id_del_profesor);

    // Extraemos las variables para que la Vista las use limpiamente
    $total_alumnos = $estadisticas_docente['total_alumnos'] ?? 0;
    $total_asignaturas = $estadisticas_docente['total_asignaturas'] ?? 0;
    $total_secciones = $estadisticas_docente['total_secciones'] ?? 0;
}
?>