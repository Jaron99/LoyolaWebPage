<!-- Modal para Agregar Profesor -->
<div class="modal fade" id="modalNuevoDocente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header bg-light border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-person-plus-fill text-warning me-2"></i>Registrar Nuevo Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="admin.view.php?tab=docentes" method="POST">
                <div class="modal-body p-4 bg-light">
                    <input type="hidden" name="accion" value="registrar_docente">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Código de Profesor (ID)</label>
                            <input type="text" name="id_profesor" class="form-control border-2" placeholder="Ej. P-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Nombres</label>
                            <input type="text" name="nombre_prof" class="form-control border-2" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Apellidos</label>
                            <input type="text" name="apellido_prof" class="form-control border-2" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Especialidad</label>
                            <input type="text" name="especialidad" class="form-control border-2" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Teléfono</label>
                            <input type="text" name="telefono" class="form-control border-2" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light rounded-bottom-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-light text-muted fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <div class="d-flex gap-2">
                        <button type="submit" name="btn_accion" value="guardar_y_continuar" class="btn btn-outline-warning fw-bold text-dark"><i class="bi bi-arrow-repeat me-1"></i> Guardar y Añadir Otro</button>
                        <button type="submit" name="btn_accion" value="guardar_y_cerrar" class="btn btn-warning fw-bold text-dark"><i class="bi bi-save-fill me-1"></i> Guardar y Salir</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Profesor -->
<?php if (isset($profe)): ?>
    <div class="modal fade text-start" id="modalEditarDocente_<?php echo $profe['id_profesor']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow rounded-3">
                <div class="modal-header bg-light border-bottom-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Editar Docente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="admin.view.php?tab=docentes" method="POST">
                    <div class="modal-body p-4 bg-light">
                        <input type="hidden" name="accion" value="editar_docente">
                        <input type="hidden" name="id_profesor" value="<?php echo $profe['id_profesor']; ?>">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted text-uppercase">Código de Profesor (ID)</label>
                                <input type="text" name="id_profesor" class="form-control border-2" value="<?php echo htmlspecialchars($profe['id_profesor']); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Nombres</label>
                                <input type="text" name="nombre_prof" class="form-control border-2" value="<?php echo htmlspecialchars($profe['nombre_prof']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Apellidos</label>
                                <input type="text" name="apellido_prof" class="form-control border-2" value="<?php echo htmlspecialchars($profe['apellido_prof']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Especialidad</label>
                                <input type="text" name="especialidad" class="form-control border-2" value="<?php echo htmlspecialchars($profe['especialidad']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">Teléfono</label>
                                <input type="text" name="telefono" class="form-control border-2" value="<?php echo htmlspecialchars($profe['telefono']); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 bg-light">
                        <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-save-fill me-1"></i> Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade text-start" id="modalEliminarDocente_<?php echo $profe['id_profesor']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-3">
                <div class="modal-body p-4 text-center bg-white rounded-3">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3 mt-2">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-dark">¿Eliminar Docente?</h5>
                    <p class="text-muted small mb-4">Vas a borrar al docente <strong><?php echo htmlspecialchars($profe['nombre_completo']); ?></strong>.</p>
                    <form action="admin.view.php?tab=docentes" method="POST" class="d-flex justify-content-center gap-2">
                        <input type="hidden" name="accion" value="eliminar_docente">
                        <input type="hidden" name="id_profesor" value="<?php echo $profe['id_profesor']; ?>">
                        <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger fw-bold px-4"><i class="bi bi-trash-fill me-1"></i> Confirmar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>