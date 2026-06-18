<?php
ob_start();
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.view.php");
    exit();
}
include_once __DIR__ . "/../controllers/admin.controller.php";
?>

<?php include_once "../utils/header.php"; ?>
<?php include_once "../utils/sidebar.php"; ?>
<!-- Contenido Principal -->
<div class="main-content" id="main-content">
    <?php include_once "../utils/topbar.php"; ?>

    <!-- Contenido de las Vistas -->
    <div class="container-fluid p-4">
        <div class="tab-content" id="v-pills-tabContent">
            <!-- Dashboard -->
            <?php include_once "../utils/dashboard.php"; ?>
            <!-- Gestión de Usuarios -->
            <?php include_once "../utils/gestion_usuarios.php"; ?>
            <!-- Grados  -->
            <?php include_once "../utils/gestion_grados.php"; ?>
            <div class="w-100">
                <?php
                // Lógica limpia: Cargamos un SOLO archivo dependiendo del clic en el sidebar
                if ($active === 'panel' || $active === 'dashboard') {
                    include_once '../utils/dashboard.php';
                } elseif ($active === 'usuarios') {
                    include_once '../utils/gestion_usuarios.php';
                } elseif ($active === 'grados') {
                    include_once '../utils/gestion_grados.php';
                } elseif ($active === 'estudiantes') {
                    include_once '../utils/gestion_estudiantes.php';
                } elseif ($active === 'docentes') {
                    include_once '../utils/gestion_docentes.php';
                }
                ?>
            </div>
            <!-- Calificaciones -->
            <?php include_once "../utils/calificaciones.php"; ?>
            <!-- Evaluación (Tabla de Notas) -->
            <?php include_once "../utils/evaluacion.php"; ?>          
            <!-- Respaldo del Sistema -->
            <?php include_once "../utils/respaldo.php"; ?>
            <!-- Configuración del Sistema -->
            <div class="tab-pane fade <?php echo ($active == 'configuracion') ? 'show active' : ''; ?>" id="vista-configuracion">

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h2 class="fw-bold text-dark mb-0">Configuración del Sistema</h2>
                        <p class="text-muted mb-0">Controle los periodos de evaluación, bloqueos de notas y accesos de seguridad.</p>
                    </div>
                    <button type="submit" form="formConfiguracion" class="btn text-white px-4 shadow-sm fw-semibold" style="background-color: var(--verde-institucional);">
                        <i class="bi bi-save-fill me-2"></i> Guardar Cambios
                    </button>
                </div>

                <form id="formConfiguracion" action="sistema_admin.php" method="POST">
                    <input type="hidden" name="accion" value="guardar_configuracion">

                    <div class="row g-4">
                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-calendar-check me-2 text-primary"></i> Control Académico</h5>
                                </div>
                                <div class="card-body p-4">

                                    <div class="row g-3 mb-4 pb-4 border-bottom">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small fw-semibold text-uppercase">Año Escolar Activo</label>
                                            <select class="form-select bg-light border-0 fw-bold">
                                                <option value="2025">2025</option>
                                                <option value="2026" selected>2026</option>
                                                <option value="2027">2027</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small fw-semibold text-uppercase">Corte Evaluativo Actual</label>
                                            <select class="form-select bg-light border-0">
                                                <option value="1">I Corte</option>
                                                <option value="2" selected>II Corte</option>
                                                <option value="3">III Corte</option>
                                                <option value="4">IV Corte</option>
                                            </select>
                                        </div>
                                    </div>

                                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-lock-fill me-2 text-danger"></i> Permisos de Ingreso de Notas (Docentes)</h6>
                                    <p class="text-muted small mb-3">Active o desactive los interruptores para permitir o bloquear que los profesores suban o modifiquen calificaciones en cada corte.</p>

                                    <div class="list-group list-group-flush border-0">
                                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-bottom-0">
                                            <span class="fw-semibold text-muted"><del>I Corte Evaluativo</del> (Finalizado)</span>
                                            <div class="form-check form-switch fs-5">
                                                <input class="form-check-input" type="checkbox" role="switch" disabled>
                                            </div>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-bottom-0">
                                            <span class="fw-bold text-success">II Corte Evaluativo (Activo)</span>
                                            <div class="form-check form-switch fs-5">
                                                <input class="form-check-input bg-success" type="checkbox" role="switch" checked>
                                            </div>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-bottom-0">
                                            <span class="fw-semibold text-dark">III Corte Evaluativo</span>
                                            <div class="form-check form-switch fs-5">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-bottom-0">
                                            <span class="fw-semibold text-dark">IV Corte Evaluativo</span>
                                            <div class="form-check form-switch fs-5">
                                                <input class="form-check-input" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-shield-lock me-2 text-warning"></i> Seguridad y Accesos</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="mb-4">
                                        <h6 class="fw-bold text-dark mb-2">Portal de Estudiantes / Padres</h6>
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" role="switch" id="verNotas" checked>
                                            <label class="form-check-label text-muted small" for="verNotas">Permitir a estudiantes ver sus notas actuales.</label>
                                        </div>
                                    </div>
                                    <hr class="text-muted my-4">
                                    <div class="mb-4">
                                        <h6 class="fw-bold text-danger mb-2">Modo Mantenimiento</h6>
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" role="switch" id="modoMantenimiento">
                                            <label class="form-check-label text-muted small" for="modoMantenimiento">Bloquear acceso al sistema a todos los usuarios (excepto Administradores).</label>
                                        </div>
                                    </div>
                                    <hr class="text-muted my-4">
                                    <div>
                                        <label class="form-label text-muted small fw-semibold text-uppercase">Cierre automático de sesión</label>
                                        <select class="form-select bg-light border-0">
                                            <option value="15">Tras 15 minutos de inactividad</option>
                                            <option value="30" selected>Tras 30 minutos de inactividad</option>
                                            <option value="60">Tras 1 hora de inactividad</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "../utils/footer.php"; ?>