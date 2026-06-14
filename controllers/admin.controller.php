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
}

?>