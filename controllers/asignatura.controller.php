<?php
require_once __DIR__ . "/../models/asignatura.model.php";

$asignaturaModel = new Asignatura();

// Obtenemos los datos de la sesión actual
$rol_usuario = $_SESSION['rol'] ?? '';
$id_referencia = $_SESSION['id_referencia'] ?? null;

// Ejecutamos la consulta
// 1. Obtenemos las asignaturas base
$listaAsignaturas = $asignaturaModel->obtenerAsignaturasPorRol($rol_usuario, $id_referencia);

// 2. NUEVO: Agrupamos las asignaturas por Grado
$gradosAsignados = [];

if (!empty($listaAsignaturas)) {
    foreach ($listaAsignaturas as $asig) {
        $id_grado = $asig['id_grado'];
        
        // Si el grado no existe en nuestra lista agrupada, lo inicializamos
        if (!isset($gradosAsignados[$id_grado])) {
            $gradosAsignados[$id_grado] = [
                'id_grado'    => $id_grado,
                'nombre_grad' => $asig['nombre_grad'],
                // Buscamos las secciones una sola vez por grado
                'secciones'   => $asignaturaModel->obtenerSeccionesPorGrado($id_grado),
                'asignaturas' => []
            ];
        }
        
        // Metemos la asignatura dentro de su grado correspondiente
        $gradosAsignados[$id_grado]['asignaturas'][] = $asig;
    }
}
?>