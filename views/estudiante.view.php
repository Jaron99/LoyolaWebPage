<?php
ob_start();
session_start();

// Control de inactividad (10 minutos)
$tiempo_limite = 600;
if (isset($_SESSION['ultimo_acceso'])) {
    $tiempo_transcurrido = time() - $_SESSION['ultimo_acceso'];
    if ($tiempo_transcurrido > $tiempo_limite) {
        session_unset(); session_destroy(); session_start();
        $_SESSION['error'] = "Tu sesión ha expirado por inactividad. Vuelve a ingresar.";
        header("Location: login.view.php"); exit();
    }
}
$_SESSION['ultimo_acceso'] = time();

// Seguridad de Rol
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: login.view.php"); exit();
}

// Cargamos el controlador que prepara los datos
include_once __DIR__ . "/../controllers/estudiantes.controller.php";

$active = $_GET['tab'] ?? 'panelestudiante';
$_GET['tab'] = $active; 
?>

<?php include_once "../utils/header.php"; ?>
<?php include_once "../utils/sidebar.php"; ?>

<div class="main-content" id="main-content">
    <?php include_once "../utils/topbar.php"; ?>

    <div class="container-fluid p-4">
        <div class="tab-content">
            <div class="w-100">
                <?php
                if ($active === 'panelestudiante') {
                    include_once '../utils/dashboard_estudiante.php';
                } else {
                    include_once '../utils/dashboard_estudiante.php';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include_once "../utils/footer.php"; ?>