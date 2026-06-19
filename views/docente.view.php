<?php
ob_start();
session_start();
// --- INICIO: LÍMITE DE INACTIVIDAD (10 MINUTOS) ---
$tiempo_limite = 600;
if (isset($_SESSION['ultimo_acceso'])) {
    $tiempo_transcurrido = time() - $_SESSION['ultimo_acceso'];
    if ($tiempo_transcurrido > $tiempo_limite) {
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['error'] = "Tu sesión ha expirado por inactividad. Por seguridad, vuelve a ingresar.";
        header("Location: login.view.php");
        exit();
    }
}
$_SESSION['ultimo_acceso'] = time(); // Actualiza el reloj cada vez que el usuario hace clic o recarga
// --- FIN: LÍMITE DE INACTIVIDAD ---
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'docente') {
    header("Location: login.view.php");
    exit();
}
include_once __DIR__ . "/../controllers/admin.controller.php";

// 1. Obtenemos qué pestaña quiere ver el usuario (por defecto dashboard)
$active = $_GET['tab'] ?? 'panel';
?>

<?php include_once "../utils/header.php"; ?>
<?php include_once "../utils/sidebar.php"; ?>

<div class="main-content" id="main-content">
    <?php include_once "../utils/topbar.php"; ?>

    <div class="container-fluid p-4">
        <div class="tab-content" id="v-pills-tabContent">
            <div class="w-100">
                <?php
                // ENRUTADOR SEGURO PARA DOCENTES
                if ($active === 'dashboard') {
                    include_once '../utils/dashboard.php';
                } elseif ($active === 'calificaciones') {
                    include_once '../utils/calificaciones.php'; // ¡Reciclado!
                } elseif ($active === 'evaluacion') {
                    include_once '../utils/evaluacion.php'; // ¡Reciclado!
                } else {
                    include_once '../utils/dashboard.php';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include_once "../utils/footer.php"; ?>