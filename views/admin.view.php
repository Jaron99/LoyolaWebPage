<?php
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
            <!-- Respaldo del Sistema -->
            <div class="tab-pane fade <?php echo ($active == 'respaldo') ? 'show active' : ''; ?>" id="vista-respaldo">

                <!-- Título y Descripción -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h2 class="fw-bold text-dark mb-0">Respaldo del Sistema</h2>
                        <p class="text-muted mb-0">Genere copias de seguridad de la base de datos o restaure información previa.</p>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Tarjeta de Exportación (Backup) -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-top: 5px solid var(--verde-institucional);">
                            <div class="card-body p-4 text-center d-flex flex-column">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                                    <i class="bi bi-cloud-arrow-down-fill fs-1 text-success"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-3">Crear Copia de Seguridad</h4>
                                <p class="text-muted small mb-4 flex-grow-1">Descarga un archivo <strong>.sql</strong> con toda la información actual de estudiantes, docentes, matrículas y calificaciones. Se recomienda realizar esta acción de forma semanal.</p>

                                <button type="button" class="btn btn-amarillo-institucional px-4 py-3 w-100 shadow-sm rounded-3">
                                    <i class="bi bi-download me-2"></i> Descargar Respaldo Ahora
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta de Importación (Restaurar) -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-top: 5px solid #dc3545;">
                            <div class="card-body p-4 text-center d-flex flex-column">
                                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                                    <i class="bi bi-cloud-arrow-up-fill fs-1 text-danger"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-3">Restaurar Sistema</h4>
                                <p class="text-muted small mb-4 flex-grow-1">Sube un archivo de respaldo previo para restaurar el sistema. <br><strong class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> Advertencia:</strong> Esto sobreescribirá todos los datos actuales.</p>

                                <div class="input-group mb-3 text-start shadow-sm rounded-3 overflow-hidden">
                                    <input type="file" class="form-control bg-light border-0 py-2" id="archivoSql" accept=".sql">
                                </div>

                                <button type="button" class="btn btn-outline-danger px-4 py-2 w-100 shadow-sm rounded-3 fw-bold">
                                    <i class="bi bi-upload me-2"></i> Subir y Restaurar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial Simulado -->
                <div class="card border-0 shadow-sm mt-4 bg-white">
                    <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-muted"></i> Últimos Respaldos Generados</h5>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <ul class="list-group list-group-flush border-0">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-bottom py-3 bg-transparent">
                                <div>
                                    <i class="bi bi-file-earmark-code-fill text-success me-3 fs-5"></i>
                                    <span class="fw-semibold text-dark">backup_cpsil_20260523.sql</span>
                                </div>
                                <span class="badge bg-light text-muted rounded-pill px-3 py-2 border">Hace 7 días</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 bg-transparent">
                                <div>
                                    <i class="bi bi-file-earmark-code-fill text-success me-3 fs-5"></i>
                                    <span class="fw-semibold text-dark">backup_cpsil_20260516.sql</span>
                                </div>
                                <span class="badge bg-light text-muted rounded-pill px-3 py-2 border">Hace 14 días</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

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