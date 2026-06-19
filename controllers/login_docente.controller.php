<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'docente') {
    header("Location: ../views/login.view.php");
    exit();
}

require_once __DIR__ . "/../models/asignatura.model.php";
$asignaturaModel = new Asignatura();

$id_referencia = $_SESSION['id_referencia'] ?? null;
$misAsignaturas = $asignaturaModel->obtenerAsignaturasPorRol('docente', $id_referencia);
$totalAsignaturas = count($misAsignaturas);

$active = isset($_GET['tab']) ? $_GET['tab'] : 'panel';