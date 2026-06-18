<?php
require_once __DIR__ . "/../models/configuracion.model.php";
$configModel = new Configuracion();
$ajustes = $configModel->obtenerAjustes();
?>

<div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'configuracion') ? 'show active' : ''; ?>" id="vista-ajustes">

    <form action="../controllers/configuracion.controller.php" method="POST">
        <input type="hidden" name="action" value="guardar_ajustes">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-0">Configuración del Sistema</h2>
                <p class="text-muted mb-0">Administre los permisos globales y el control de calificaciones.</p>
            </div>
            <div>
                <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                    <i class="bi bi-save-fill me-2"></i> Guardar Cambios
                </button>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-unlock-fill me-2 text-primary"></i> Cortes Evaluativos</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">
                            Active los interruptores para permitir a los docentes ingresar o editar calificaciones en sus respectivas materias. Si el interruptor está apagado, las celdas aparecerán bloqueadas (solo lectura).
                        </p>

                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                            <div>
                                <h6 class="fw-bold mb-1">I Parcial</h6>
                                <small class="text-muted">Habilitar edición de notas</small>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" name="editar_i_parcial" <?php echo ($ajustes['editar_i_parcial']) ? 'checked' : ''; ?>>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                            <div>
                                <h6 class="fw-bold mb-1">II Parcial</h6>
                                <small class="text-muted">Habilitar edición de notas</small>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" name="editar_ii_parcial" <?php echo ($ajustes['editar_ii_parcial']) ? 'checked' : ''; ?>>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                            <div>
                                <h6 class="fw-bold mb-1">III Parcial</h6>
                                <small class="text-muted">Habilitar edición de notas</small>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" name="editar_iii_parcial" <?php echo ($ajustes['editar_iii_parcial']) ? 'checked' : ''; ?>>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pb-2">
                            <div>
                                <h6 class="fw-bold mb-1">IV Parcial</h6>
                                <small class="text-muted">Habilitar edición de notas</small>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" name="editar_iv_parcial" <?php echo ($ajustes['editar_iv_parcial']) ? 'checked' : ''; ?>>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-flex flex-column gap-4">

                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                        <h6 class="fw-bold text-primary mb-0 text-uppercase small"><i class="bi bi-building me-2"></i> Datos Generales</h6>
                    </div>
                    <div class="card-body p-4">
                        <label class="form-label fw-bold mb-1">Año Lectivo Actual</label>
                        <input type="number" name="ano_lectivo" class="form-control border-2 w-50" value="<?php echo htmlspecialchars($ajustes['ano_lectivo'] ?? date('Y')); ?>" required>
                        <small class="text-muted d-block mt-2">Este año aparecerá en los boletines y reportes oficiales.</small>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                        <h6 class="fw-bold text-success mb-0 text-uppercase small"><i class="bi bi-eye-fill me-2"></i> Visibilidad de Calificaciones</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">Publicar Notas a Estudiantes</h6>
                                <small class="text-muted">Si está apagado, verán el mensaje de "Notas en proceso".</small>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input border-secondary" type="checkbox" role="switch" name="publicar_notas" <?php echo (isset($ajustes['publicar_notas']) && $ajustes['publicar_notas']) ? 'checked' : ''; ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius: 12px; border-top: 4px solid #dc3545 !important;">
                    <div class="card-header bg-white border-bottom pt-3 pb-2 px-4">
                        <h6 class="fw-bold text-danger mb-0 text-uppercase small"><i class="bi bi-shield-lock-fill me-2"></i> Seguridad del Sistema</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-danger mb-1">Modo Mantenimiento</h6>
                                <small class="text-danger opacity-75">Bloquea el inicio de sesión para docentes y estudiantes.</small>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input bg-danger border-danger" type="checkbox" role="switch" name="modo_mantenimiento" <?php echo (isset($ajustes['modo_mantenimiento']) && $ajustes['modo_mantenimiento']) ? 'checked' : ''; ?>>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>