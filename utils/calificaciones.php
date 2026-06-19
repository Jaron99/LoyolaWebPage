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

    <div class="accordion shadow-sm" id="acordeonGrados" style="border-radius: 12px;">
        <?php if (!empty($gradosAsignados)): ?>

            <?php $acc_index = 0; ?>
            <?php foreach ($gradosAsignados as $grado): ?>
                <?php $acc_index++; ?>

                <div class="accordion-item border-0 border-bottom">

                    <h2 class="accordion-header" id="heading_<?php echo $acc_index; ?>">
                        <button class="accordion-button collapsed fw-bold fs-5 text-dark bg-white btn-acordeon-manual" type="button" data-panel-id="collapse_<?php echo $acc_index; ?>">
                            <i class="bi bi-mortarboard-fill text-primary me-3 fs-4"></i>
                            <?php echo htmlspecialchars($grado['nombre_grad']); ?>
                            <span class="badge bg-primary rounded-pill ms-auto" style="font-size: 0.8rem;">
                                <?php echo count($grado['asignaturas']); ?> Materia(s)
                            </span>
                        </button>
                    </h2>

                    <div id="collapse_<?php echo $acc_index; ?>" class="accordion-collapse collapse" data-bs-parent="#acordeonGrados">
                        <div class="accordion-body p-0">
                            <div class="list-group list-group-flush">

                                <?php foreach ($grado['asignaturas'] as $asig): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center p-3 transition-hover bg-light">
                                        <div>
                                            <h6 class="fw-bold mb-1 text-dark">
                                                <i class="bi bi-journal-bookmark-fill text-primary me-2"></i><?php echo htmlspecialchars($asig['nombre_asig']); ?>
                                                <span class="badge bg-white text-secondary border ms-2 fw-normal"><?php echo htmlspecialchars($asig['id_asig']); ?></span>
                                            </h6>
                                            <?php if ($rol_usuario === 'admin' && !empty($asig['nombre_prof'])): ?>
                                                <small class="text-muted ms-4"><i class="bi bi-person-badge me-1"></i> Docente: <?php echo htmlspecialchars($asig['nombre_prof'] . ' ' . $asig['apellido_prof']); ?></small>
                                            <?php endif; ?>
                                        </div>

                                        <div>
                                            <button class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalConfig_<?php echo $asig['id_asig']; ?>">
                                                Ingresar Notas <i class="bi bi-chevron-down ms-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 text-muted bg-white" style="border-radius: 12px;">
                <div class="bg-light rounded-circle d-inline-flex p-4 mb-3"><i class="bi bi-journal-x fs-1"></i></div>
                <h6 class="fw-bold">No hay asignaturas</h6>
                <p class="small">No tienes clases asignadas en este momento.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($gradosAsignados)): ?>
    <?php foreach ($gradosAsignados as $grado): ?>
        <?php foreach ($grado['asignaturas'] as $asig): ?>

            <div class="modal fade" id="modalConfig_<?php echo $asig['id_asig']; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-light border-0 pt-4 px-4">
                            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-sliders me-2 text-primary"></i>Configurar Evaluación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <?php $vista_destino = ($rol_usuario === 'admin') ? 'admin.view.php' : 'docente.view.php'; ?>
                        <form action="<?php echo $vista_destino; ?>" method="GET">
                            <input type="hidden" name="tab" value="evaluacion">
                            <input type="hidden" name="id_asig" value="<?php echo $asig['id_asig']; ?>">
                            <input type="hidden" name="nombre_asig" value="<?php echo htmlspecialchars($asig['nombre_asig']); ?>">

                            <div class="modal-body p-4 bg-light">
                                <div class="mb-3 border-bottom pb-3">
                                    <h6 class="fw-bold text-primary mb-0"><?php echo htmlspecialchars($asig['nombre_asig']); ?></h6>
                                    <span class="text-muted small"><?php echo htmlspecialchars($grado['nombre_grad']); ?></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Seleccione la Sección</label>
                                    <select name="id_seccion" class="form-select border-2" required>
                                        <option value="" disabled selected>-- Elegir Sección --</option>
                                        <?php if (!empty($grado['secciones'])): ?>
                                            <?php foreach ($grado['secciones'] as $sec): ?>
                                                <option value="<?php echo $sec['id_seccion']; ?>">Sección "<?php echo htmlspecialchars($sec['nombre_sec']); ?>"</option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="" disabled>No hay secciones creadas</option>
                                        <?php endif; ?>
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
    <?php endforeach; ?>
<?php endif; ?>

<style>
    .transition-hover {
        transition: background-color 0.2s ease;
    }

    .transition-hover:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const botones = document.querySelectorAll('.btn-acordeon-manual');

        botones.forEach(boton => {
            boton.addEventListener('click', function(e) {
                e.preventDefault();

                // Buscamos qué panel le pertenece a este botón
                const panelId = this.getAttribute('data-panel-id');
                const panel = document.getElementById(panelId);

                if (panel.classList.contains('show')) {
                    // Si está abierto, lo cerramos
                    panel.classList.remove('show');
                    this.classList.add('collapsed');
                } else {
                    // Si está cerrado, lo abrimos
                    panel.classList.add('show');
                    this.classList.remove('collapsed');
                }
            });
        });
    });
</script>