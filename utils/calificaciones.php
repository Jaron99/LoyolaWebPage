<?php 
include_once __DIR__ . "/../controllers/asignatura.controller.php"; 
?>

<div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'calificaciones') ? 'show active' : ''; ?>" id="vista-calificaciones">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Mis Clases Asignadas</h2>
            <p class="text-muted mb-0">Seleccione la asignatura que desea evaluar.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="border-radius: 12px; overflow: hidden;">
        <div class="list-group list-group-flush">
            <?php if (!empty($listaAsignaturas)): ?>
                <?php foreach ($listaAsignaturas as $asig): ?>
                    
                    <div class="list-group-item d-flex justify-content-between align-items-center p-3 transition-hover">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">
                                <i class="bi bi-journal-bookmark-fill text-primary me-2"></i><?php echo htmlspecialchars($asig['nombre_asig']); ?>
                                <span class="badge bg-light text-secondary border ms-2 fw-normal"><?php echo htmlspecialchars($asig['id_asig']); ?></span>
                            </h6>
                            <small class="text-muted ms-4"><i class="bi bi-mortarboard-fill me-1"></i> <?php echo htmlspecialchars($asig['nombre_grad']); ?></small>
                            <?php if ($rol_usuario === 'admin' && !empty($asig['nombre_prof'])): ?>
                                <small class="text-muted ms-3 border-start ps-3"><i class="bi bi-person-badge me-1"></i> <?php echo htmlspecialchars($asig['nombre_prof'] . ' ' . $asig['apellido_prof']); ?></small>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <button class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalConfig_<?php echo $asig['id_asig']; ?>">
                                Ingresar Notas <i class="bi bi-chevron-down ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <div class="modal fade" id="modalConfig_<?php echo $asig['id_asig']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-light border-0 pt-4 px-4">
                                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-sliders me-2 text-primary"></i>Configurar Evaluación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                
                                <form action="admin.view.php" method="GET">
                                    <input type="hidden" name="tab" value="evaluacion">
                                    <input type="hidden" name="id_asig" value="<?php echo $asig['id_asig']; ?>">
                                    
                                    <div class="modal-body p-4 bg-light">
                                        <div class="mb-3 border-bottom pb-3">
                                            <h6 class="fw-bold text-primary mb-0"><?php echo htmlspecialchars($asig['nombre_asig']); ?></h6>
                                            <span class="text-muted small"><?php echo htmlspecialchars($asig['nombre_grad']); ?></span>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Seleccione la Sección</label>
                                            <select name="id_seccion" class="form-select border-2" required>
                                                <option value="" disabled selected>-- Elegir Sección --</option>
                                                <?php if(!empty($asig['secciones'])): ?>
                                                    <?php foreach($asig['secciones'] as $sec): ?>
                                                        <option value="<?php echo $sec['id_seccion']; ?>">Sección "<?php echo htmlspecialchars($sec['nombre_sec']); ?>"</option>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <option value="" disabled>No hay secciones creadas</option>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Periodo Evaluativo</label>
                                            <select name="parcial" class="form-select border-2" required>
                                                <option value="" disabled selected>-- Elegir Parcial --</option>
                                                <option value="I Parcial">I Parcial</option>
                                                <option value="II Parcial">II Parcial</option>
                                                <option value="III Parcial">III Parcial</option>
                                                <option value="IV Parcial">IV Parcial</option>
                                                <option value="Nota Final">Nota Final / Convocatoria</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light border-0 pb-4 px-4 d-flex justify-content-between">
                                        <button type="button" class="btn btn-white text-muted fw-bold" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm">Cargar Alumnos <i class="bi bi-arrow-right-short ms-1"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <div class="bg-light rounded-circle d-inline-flex p-4 mb-3"><i class="bi bi-journal-x fs-1"></i></div>
                    <h6 class="fw-bold">No hay asignaturas</h6>
                    <p class="small">No tienes clases asignadas en este momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.transition-hover { transition: background-color 0.2s ease; }
.transition-hover:hover { background-color: #f8f9fa; cursor: pointer; }
</style>