<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') { header("Location: ../index.php"); exit(); }

require_once __DIR__ . "/../models/respaldo.model.php";
$respaldoModel = new Respaldo();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // ACCIÓN 1: DESCARGAR BACKUP
    if ($_POST['action'] === 'backup') {
        $contenido_sql = $respaldoModel->generarRespaldoSQL();
        $nombre_archivo = 'backup_cpsil_' . date("Ymd_His") . '.sql';

        // Guardamos en el historial que se hizo un backup
        $respaldoModel->registrarAccion($nombre_archivo, 'BACKUP');

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"".$nombre_archivo."\""); 
        echo $contenido_sql;
        exit(); // Evita que se recargue la página, solo descarga el archivo
    }

    // ACCIÓN 2: RESTAURAR SISTEMA
    if ($_POST['action'] === 'restore' && isset($_FILES['archivo_sql'])) {
        $archivo = $_FILES['archivo_sql'];

        // Validamos que sea un archivo SQL
        $ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        if ($ext === 'sql' && $archivo['error'] === UPLOAD_ERR_OK) {
            
            // Leemos el contenido del archivo subido
            $contenido = file_get_contents($archivo['tmp_name']);
            
            if ($respaldoModel->restaurarRespaldo($contenido)) {
                // Registramos en el historial que se hizo una restauración
                $respaldoModel->registrarAccion($archivo['name'], 'RESTORE');
                header("Location: ../views/admin.view.php?tab=respaldo&msg=restauracion_exitosa");
            } else {
                header("Location: ../views/admin.view.php?tab=respaldo&msg=error_restauracion");
            }
        } else {
            header("Location: ../views/admin.view.php?tab=respaldo&msg=archivo_invalido");
        }
        exit();
    }
} else {
    header("Location: ../views/admin.view.php?tab=respaldo");
    exit();
}
?>