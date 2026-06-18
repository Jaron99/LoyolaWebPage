<?php include_once __DIR__ . "/../controllers/estudiantes.controller.php"; ?>

<style>
    /* Separar los controles de DataTables de la tabla para que no se vean amontonados */
    .dataTables_wrapper .row:first-child { margin-bottom: 15px; }
    .dataTables_wrapper .row:last-child { margin-top: 15px; }
    
    /* Estilizar el input de búsqueda automático para que parezca de Bootstrap 5 */
    .dataTables_filter input {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 5px 10px;
        transition: all 0.2s;
    }
    .dataTables_filter input:focus {
        border-color: var(--amarillo-institucional);
        outline: none;
        box-shadow: 0 0 0 0.25rem rgba(255, 215, 9, 0.25);
    }
</style>

<div class="tab-pane fade show active" id="vista-estudiantes">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">Gestión de Estudiantes</h2>
            <p class="text-muted mb-0">Administre el directorio de alumnos, matrículas y datos personales.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success shadow-sm fw-semibold" title="Importar desde Excel (Próximamente)">
                <i class="bi bi-file-earmark-spreadsheet-fill me-2"></i> Importar Excel
            </button>
            <button class="btn btn-amarillo-institucional shadow-sm text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#modalNuevoEstudiante">
                <i class="bi bi-person-plus-fill me-2"></i> Nuevo Estudiante
            </button>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 small fw-bold d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>
                <?php
                if ($_GET['msg'] === 'creado') echo "Estudiante registrado exitosamente.";
                if ($_GET['msg'] === 'creado_continuar') echo "Estudiante registrado. Puede ingresar el siguiente.";
                if ($_GET['msg'] === 'actualizado') echo "Datos del estudiante actualizados correctamente.";
                if ($_GET['msg'] === 'eliminado') echo "El estudiante ha sido eliminado del sistema.";
                ?>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 tabla-datatable" style="width:100%">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="px-4 py-3">Código MINED</th>
                        <th class="py-3">Nombre Completo</th>
                        <th class="py-3">Grado Actual</th>
                        <th class="py-3">Teléfono</th>
                        <th class="py-3">Fecha Nac.</th>
                        <th class="text-end px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($listaEstudiantes)): ?>
                        <?php foreach ($listaEstudiantes as $alumno): ?>
                            <tr>
                                <td class="px-4 fw-bold text-primary">
                                    <?php echo htmlspecialchars($alumno['cod_mined'] ?? 'N/A'); ?>
                                </td>
                                <td class="fw-semibold text-dark">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                            <?php echo substr($alumno['nombre_completo'], 0, 1); ?>
                                        </div>
                                        <?php echo htmlspecialchars($alumno['nombre_completo']); ?>
                                    </div>
                                </td>

                                <td>
                                    <?php if (!empty($alumno['grado_asignado'])): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success fw-bold">
                                            <i class="bi bi-journal-bookmark-fill me-1"></i> <?php echo htmlspecialchars($alumno['grado_asignado']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">
                                            <i class="bi bi-exclamation-circle me-1"></i> Sin Matricular
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="text-muted small"><i class="bi bi-telephone-fill me-1"></i> <?php echo htmlspecialchars($alumno['telefono'] ?? ''); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><i class="bi bi-calendar3 me-1"></i> <?php echo htmlspecialchars($alumno['fecha_nac'] ?? 'N/D'); ?></span>
                                </td>
                                <td class="text-end px-4">
                                    <div class="btn-group">
                                        <button class="btn btn-outline-primary btn-sm shadow-sm" title="Editar Estudiante" data-bs-toggle="modal" data-bs-target="#modalEditarEstudiante_<?php echo $alumno['id_alumno']; ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm shadow-sm" title="Eliminar Estudiante" data-bs-toggle="modal" data-bs-target="#modalEliminarEstudiante_<?php echo $alumno['id_alumno']; ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade text-start" id="modalEditarEstudiante_<?php echo $alumno['id_alumno']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow rounded-3">
                                        <div class="modal-header bg-light border-bottom-0 pt-4 px-4">
                                            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Editar Estudiante</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="admin.view.php?tab=estudiantes" method="POST">
                                            <div class="modal-body p-4 bg-light">
                                                <input type="hidden" name="accion" value="editar_estudiante">
                                                <input type="hidden" name="id_alumno" value="<?php echo $alumno['id_alumno']; ?>">

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small text-muted">Nombres</label>
                                                        <input type="text" name="nombres" class="form-control border-2" value="<?php echo htmlspecialchars($alumno['nombres'] ?? ''); ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small text-muted">Apellidos</label>
                                                        <input type="text" name="apellidos" class="form-control border-2" value="<?php echo htmlspecialchars($alumno['apellidos'] ?? ''); ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small text-muted">Código MINED</label>
                                                        <input type="text" name="cod_mined" class="form-control border-2" value="<?php echo htmlspecialchars($alumno['cod_mined'] ?? ''); ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small text-muted">Fecha de Nacimiento</label>
                                                        <input type="date" name="fecha_nac" class="form-control border-2" value="<?php echo htmlspecialchars($alumno['fecha_nac'] ?? ''); ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small text-muted">Teléfono</label>
                                                        <input type="text" name="telefono" class="form-control border-2" value="<?php echo htmlspecialchars($alumno['telefono'] ?? ''); ?>" required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-bold small text-muted">Dirección Exacta</label>
                                                        <textarea name="direccion" class="form-control border-2" rows="2" required><?php echo htmlspecialchars($alumno['direccion'] ?? ''); ?></textarea>
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

                            <div class="modal fade text-start" id="modalEliminarEstudiante_<?php echo $alumno['id_alumno']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-3">
                                        <div class="modal-body p-4 text-center bg-white rounded-3">
                                            <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3 mt-2">
                                                <i class="bi bi-exclamation-triangle-fill text-danger fs-1"></i>
                                            </div>
                                            <h5 class="fw-bold text-dark">¿Eliminar a este estudiante?</h5>
                                            <p class="text-muted small mb-4">
                                                Estás a punto de borrar a <strong><?php echo htmlspecialchars($alumno['nombre_completo']); ?></strong>.<br>
                                                Esta acción es irreversible y borrará todo su historial.
                                            </p>
                                            <form action="admin.view.php?tab=estudiantes" method="POST" class="d-flex justify-content-center gap-2">
                                                <input type="hidden" name="accion" value="eliminar_estudiante">
                                                <input type="hidden" name="id_alumno" value="<?php echo $alumno['id_alumno']; ?>">
                                                <button type="button" class="btn btn-light fw-bold shadow-sm px-4" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-danger fw-bold shadow-sm px-4">
                                                    <i class="bi bi-trash-fill me-1"></i> Sí, eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-1 d-block mb-2 text-opacity-50"></i>
                                <h6 class="fw-bold">No se encontraron estudiantes</h6>
                                <p class="small">Intenta buscar con otro nombre o registra un nuevo estudiante.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="modalNuevoEstudiante" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header bg-light border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-person-plus-fill text-warning me-2"></i>Registrar Nuevo Estudiante
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="admin.view.php?tab=estudiantes" method="POST">
                <div class="modal-body p-4 bg-light">
                    <input type="hidden" name="accion" value="registrar_estudiante">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nombres</label>
                            <input type="text" name="nombres" class="form-control border-2" placeholder="Ej. Juan Carlos" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control border-2" placeholder="Ej. Pérez Gómez" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Código MINED</label>
                            <input type="text" name="cod_mined" class="form-control border-2" placeholder="Opcional / Requerido" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nac" class="form-control border-2" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Teléfono de Contacto</label>
                            <input type="text" name="telefono" class="form-control border-2" placeholder="Ej. 8888-8888" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Dirección Exacta</label>
                            <textarea name="direccion" class="form-control border-2" rows="2" placeholder="Domicilio completo del estudiante" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 bg-light rounded-bottom-3 d-flex gap-2 justify-content-between">
                    <button type="button" class="btn btn-light shadow-sm text-muted fw-bold" data-bs-dismiss="modal">Cancelar</button>

                    <div class="d-flex gap-2">
                        <button type="submit" name="btn_accion" value="guardar_y_continuar" class="btn btn-outline-warning shadow-sm px-4 fw-bold text-dark">
                            <i class="bi bi-arrow-repeat me-1"></i> Guardar y Añadir Otro
                        </button>
                        <button type="submit" name="btn_accion" value="guardar_y_cerrar" class="btn btn-warning shadow-sm px-4 fw-bold text-dark">
                            <i class="bi bi-save-fill me-1"></i> Guardar y Salir
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'creado_continuar'): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var modalNuevo = new bootstrap.Modal(document.getElementById('modalNuevoEstudiante'));
            modalNuevo.show();
        });
    </script>
<?php endif; ?>