<?php include_once __DIR__ . "/../controllers/docentes.controller.php"; ?>

<style>
    /* Separar los controles de DataTables de la tabla para que no se vean amontonados */
    .dataTables_wrapper .row:first-child {
        margin-bottom: 15px;
    }

    .dataTables_wrapper .row:last-child {
        margin-top: 15px;
    }

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
<div class="tab-pane fade show active" id="vista-docentes">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">Gestión de Docentes</h2>
            <p class="text-muted mb-0">Administre el personal académico, especialidades y contactos.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success shadow-sm fw-semibold">
                <i class="bi bi-file-earmark-spreadsheet-fill me-2"></i> Importar Excel
            </button>
            <button class="btn btn-amarillo-institucional shadow-sm text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#modalNuevoDocente">
                <i class="bi bi-person-plus-fill me-2"></i> Nuevo Docente
            </button>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 small fw-bold d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>
                <?php
                if ($_GET['msg'] === 'creado') echo "Docente registrado exitosamente.";
                if ($_GET['msg'] === 'creado_continuar') echo "Docente registrado. Puede ingresar el siguiente.";
                if ($_GET['msg'] === 'actualizado') echo "Datos del docente actualizados correctamente.";
                if ($_GET['msg'] === 'eliminado') echo "El docente ha sido dado de baja del sistema.";
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
                        <th class="px-4 py-3">Nombre Completo</th>
                        <th class="py-3">ID del Maestro</th>
                        <th class="py-3">Especialidad</th>
                        <th class="py-3">Teléfono</th>
                        <th class="text-end px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($listaDocentes)): ?>
                        <?php foreach ($listaDocentes as $profe): ?>
                            <tr>
                                <td class="px-4 fw-semibold text-dark">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                            <?php echo substr($profe['nombre_completo'], 0, 1); ?>
                                        </div>
                                        <?php echo htmlspecialchars($profe['nombre_completo']); ?>
                                    </div>
                                </td>
                                <td class="px-4 fw-semibold text-dark">
                                    <div class="d-flex align-items-center gap-2">
                                        <?php echo htmlspecialchars($profe['id_profesor']); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info fw-bold px-3 py-2">
                                        <i class="bi bi-briefcase-fill me-1"></i> <?php echo htmlspecialchars($profe['especialidad']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted small"><i class="bi bi-telephone-fill me-1"></i> <?php echo htmlspecialchars($profe['telefono'] ?? 'N/D'); ?></span>
                                </td>
                                <td class="text-end px-4">
                                    <div class="btn-group">
                                        <button class="btn btn-outline-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEditarDocente_<?php echo $profe['id_profesor']; ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEliminarDocente_<?php echo $profe['id_profesor']; ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <?php include "modales_docentes.php"; ?>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No se encontraron docentes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include "modales_docentes.php"; ?>

<script>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'creado_continuar'): ?>
        var modalNuevo = new bootstrap.Modal(document.getElementById('modalNuevoDocente'));
        modalNuevo.show();
    <?php endif; ?>
</script>