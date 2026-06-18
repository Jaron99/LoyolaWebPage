<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') { header("Location: ../index.php"); exit(); }

require_once __DIR__ . "/../models/configuracion.model.php";
$configModel = new Configuracion();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'guardar_ajustes') {
    
    // Si un switch de HTML no está marcado, no viaja en el POST, por eso usamos isset()
    $ajustes = [
        'editar_i_parcial'   => isset($_POST['editar_i_parcial']),
        'editar_ii_parcial'  => isset($_POST['editar_ii_parcial']),
        'editar_iii_parcial' => isset($_POST['editar_iii_parcial']),
        'editar_iv_parcial'  => isset($_POST['editar_iv_parcial']),
        'modo_mantenimiento' => isset($_POST['modo_mantenimiento']),
        'publicar_notas'     => isset($_POST['publicar_notas']),
        'ano_lectivo'        => trim($_POST['ano_lectivo'])
    ];

    $configModel->actualizarAjustes($ajustes);
    
    header("Location: ../views/admin.view.php?tab=configuracion&msg=ajustes_guardados");
    exit();
} else {
    header("Location: ../views/admin.view.php?tab=configuracion");
    exit();
}
?>