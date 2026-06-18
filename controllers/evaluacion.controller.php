<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . "/../models/evaluacion.model.php";

$evaluacionModel = new Evaluacion();

// ==========================================
// 1. PROCESAR GUARDADO DE NOTAS (Vía POST)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'guardar_notas') {
    $id_asig = $_POST['id_asig'] ?? '';
    $notas = $_POST['notas'] ?? []; // Ahora es una matriz [parcial][id_matricula]

    if (!empty($id_asig) && !empty($notas)) {
        foreach ($notas as $parcial => $lista_notas) {
            foreach ($lista_notas as $id_matricula => $nota) {
                if (trim($nota) !== '') {
                    $evaluacionModel->guardarNota($id_matricula, $id_asig, $nota, $parcial);
                }
            }
        }
    }
    $ruta = ($_SESSION['rol'] === 'admin') ? 'admin.view.php' : 'docente.view.php';
    header("Location: ../views/$ruta?tab=calificaciones&msg=notas_guardadas");
    exit();
}

// ==========================================
// 2. CARGAR ALUMNOS PARA LA VISTA (Vía GET)
// ==========================================
$alumnos = [];
$id_asig = $_GET['id_asig'] ?? '';
$id_seccion = $_GET['id_seccion'] ?? '';
$nombre_asig = $_GET['nombre_asig'] ?? '';

// CONFIGURACIÓN DE BLOQUEO: true = Editable, false = Bloqueado (Solo lectura)
$parciales_status = [
    'I Parcial'   => false,
    'II Parcial'  => false,
    'III Parcial' => false,
    'IV Parcial'  => false
];
$lista_parciales = ['I Parcial', 'II Parcial', 'III Parcial', 'IV Parcial'];

if (!empty($id_asig) && !empty($id_seccion)) {
    $alumnos_raw = $evaluacionModel->obtenerAlumnosParaEvaluar($id_seccion, $id_asig);
    
    // Agrupar todas las notas por alumno
    foreach ($alumnos_raw as $row) {
        $id_mat = $row['id_matricula'];
        if (!isset($alumnos[$id_mat])) {
            $alumnos[$id_mat] = [
                'id_matricula' => $id_mat,
                'nombres' => $row['nombres'],
                'apellidos' => $row['apellidos'],
                'notas' => []
            ];
        }
        if ($row['parcial'] != null) {
            $alumnos[$id_mat]['notas'][$row['parcial']] = $row['nota'];
        }
    }
}
$es_preescolar = (strpos($id_asig, 'PRE') !== false) ? 'true' : 'false';
?>