<?php include_once __DIR__ . "/../controllers/admin.controller.php"; ?>
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

<!-- Grados y Secciones -->
<div class="tab-pane fade <?php echo ($active == 'grados') ? 'show active' : ''; ?>" id="vista-grados">
    <!-- Título y Descripción -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">Grados y Secciones</h2>
            <p class="text-muted mb-0">Gestione la estructura académica, turnos y asignaturas.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-amarillo-institucional shadow-sm text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#modalNuevoGrado">
                <i class="bi bi-plus-circle-fill me-2"></i> Nuevo Grado
            </button>
        </div>
    </div>
    <?php
    if (isset($_SESSION['error_grado'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 small fw-bold d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
            <div><?php echo $_SESSION['error_grado'];
                    unset($_SESSION['error_grado']); ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tabla de Grados y Secciones -->
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 tabla-datatable" style="width:100%">
                    <thead class="table-light text-muted" style="font-size: 0.85rem; text-transform: uppercase;">
                        <tr>
                            <th class="ps-4 py-3">Grado y Sección</th>
                            <th class="py-3">Nivel Académico</th>
                            <th class="py-3">Turno</th>
                            <th class="py-3">Alumnos</th>
                            <th class="py-3">Maestro Guía</th>
                            <th class="text-end pe-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($listaGrados)): ?>
                            <?php foreach ($listaGrados as $fila): ?>
                                <tr>
                                    <!--  -->
                                    <td class="ps-4 fw-bold text-dark">
                                        <?php

                                        $grado = $fila['nombre_grad'] ?? 'Grado Desconocido';
                                        $seccion = $fila['nombre_sec'] ?? $fila['nombre_seccion'] ?? '';

                                        echo $grado . ' ' . $seccion . '';
                                        ?>
                                    </td>

                                    <td><?php echo ucfirst($fila['modalidad'] ?? 'Sin asignar'); ?></td>
                                    <td>
                                        <span class="badge <?php echo ($fila['turno'] == 'Matutino') ? 'bg-success' : 'bg-primary'; ?> bg-opacity-10 <?php echo ($fila['turno'] == 'Matutino') ? 'text-success' : 'text-primary'; ?> rounded-pill px-3 py-2">
                                            <?php echo $fila['turno']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $totalAlumnos = $fila['total_alumnos'] ?? 0;
                                        echo $totalAlumnos . ' Alumno' . ($totalAlumnos != 1 ? 's' : '');
                                        ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($fila['nombre_profesor'])): ?>
                                            <span class="text-primary fw-bold small">
                                                <i class="bi bi-person-check-fill me-1"></i>
                                                <?php echo htmlspecialchars($fila['nombre_profesor']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic small">
                                                <i class="bi bi-person-x me-1"></i> Sin asignar
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <!-- Botón de Ver Alumnos -->
                                        <form method="POST" action="admin.view.php?tab=grados" class="d-inline">
                                            <input type="hidden" name="accion" value="ver_alumnos">
                                            <input type="hidden" name="id_seccion" value="<?php echo htmlspecialchars($fila['id_seccion'] ?? $fila['id_grado_seccion']); ?>">

                                            <button type="submit" class="btn btn-sm btn-light text-success shadow-sm" title="Ver alumnos matriculados">
                                                <i class="bi bi-journals"></i>
                                            </button>
                                        </form>
                                        <!-- Botón de Matricular Alumnos-->
                                        <form method="POST" action="admin.view.php?tab=grados" class="d-inline">
                                            <input type="hidden" name="accion" value="preparar_matricula">
                                            <input type="hidden" name="id_seccion" value="<?php echo htmlspecialchars($fila['id_seccion'] ?? $fila['id_grado_seccion']); ?>">
                                            <input type="hidden" name="nombre_grado" value="<?php echo htmlspecialchars($grado . ' ' . $seccion); ?>">

                                            <button type="submit" class="btn btn-outline-success btn-sm shadow-sm" title="Matricular alumnos">
                                                <i class="bi bi-person-plus"></i>
                                            </button>
                                        </form>
                                        <!-- Botón de Editar Grado -->
                                        <form method="POST" action="admin.view.php?tab=grados" class="d-inline">
                                            <input type="hidden" name="accion" value="preparar_editar_grado">
                                            <input type="hidden" name="id_seccion" value="<?php echo htmlspecialchars($fila['id_seccion'] ?? $fila['id_grado_seccion']); ?>">
                                            <input type="hidden" name="grado_nombre" value="<?php echo htmlspecialchars($grado); ?>">
                                            <input type="hidden" name="seccion_nombre" value="<?php echo htmlspecialchars($seccion); ?>">
                                            <input type="hidden" name="id_profesor_actual" value="<?php echo htmlspecialchars($fila['id_profesor'] ?? ''); ?>">

                                            <button type="submit" class="btn btn-sm btn-light text-primary me-1 shadow-sm" title="Configurar Grado y Traslados">
                                                <i class="bi bi-gear-fill"></i>
                                            </button>
                                        </form>
                                        <!-- Botón de Eliminar Grado -->
                                        <button type="button" class="btn btn-outline-danger btn-sm shadow-sm" title="Eliminar grado" data-bs-toggle="modal" data-bs-target="#modalEliminar_<?php echo $fila['id_seccion']; ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                        <div class="modal fade" id="modalEliminar_<?php echo $fila['id_seccion']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow rounded-3">
                                                    <div class="modal-body p-4 text-center bg-white rounded-3">

                                                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3 mt-2">
                                                            <i class="bi bi-exclamation-triangle-fill text-danger fs-1"></i>
                                                        </div>

                                                        <h5 class="fw-bold text-dark">¿Eliminar esta sección?</h5>
                                                        <p class="text-muted small mb-4">
                                                            Estás a punto de borrar <strong><?php echo htmlspecialchars($grado . ' ' . $seccion); ?></strong>.<br>
                                                            Los estudiantes inscritos perderán su matrícula y quedarán libres en el sistema.
                                                        </p>

                                                        <form action="admin.view.php?tab=grados" method="POST" class="d-flex justify-content-center gap-2">
                                                            <input type="hidden" name="accion" value="eliminar_grado">
                                                            <input type="hidden" name="id_seccion" value="<?php echo $fila['id_seccion']; ?>">

                                                            <button type="button" class="btn btn-light fw-bold shadow-sm px-4" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger fw-bold shadow-sm px-4">
                                                                <i class="bi bi-trash-fill me-1"></i> Sí, eliminar
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No se encontraron grados con este filtro.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
        </div>
    </div>
</div>

<!-- Modal para mostrar alumnos matriculados -->
<?php if ($mostrarModalAlumnos): ?>
    <div class="modal fade show" id="modalAlumnos" tabindex="-1" style="display:block; background:rgba(0,0,0,.5);" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow rounded-3">

                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4 bg-light rounded-top-3">
                    <div class="d-flex align-items-center w-100">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3 text-success">
                            <i class="bi bi-people-fill fs-3"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-1">Alumnos Matriculados</h5>
                            <p class="text-muted small mb-0">Listado oficial de estudiantes en esta sección</p>
                        </div>
                        <a href="admin.view.php?tab=grados" class="btn-close ms-auto"></a>
                    </div>
                </div>

                <div class="modal-body p-4 bg-light">

                    <?php if (!empty($alumnosMatriculados)): ?>
                        <div class="list-group list-group-flush border-0 rounded-3 shadow-sm">

                            <?php foreach ($alumnosMatriculados as $alumno):
                                // Ajusta estos nombres según las columnas de tu vista 'vw_lista_alumnos'
                                $nom = $alumno['nombres'] ?? '';
                                $ape = $alumno['apellidos'] ?? '';
                                $nombreCompleto = htmlspecialchars($nom . ' ' . $ape);

                                // Generar iniciales dinámicas
                                $iniciales = strtoupper(substr($nom, 0, 1) . substr($ape, 0, 1));
                                if (empty($iniciales)) $iniciales = "ST";

                                // Variables
                                $codigo = htmlspecialchars($alumno['id_alumno'] ?? $alumno['codigo_mined'] ?? 'N/A');
                                $estado = $alumno['estado'] ?? '1';
                            ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 border-bottom border-light bg-white">
                                    <div class="d-flex align-items-center <?php echo ($estado != '1') ? 'opacity-50' : ''; ?>">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white me-3 fw-bold shadow-sm"
                                            style="width: 45px; height: 45px; background-color: <?php echo ($estado == '1') ? '#198754' : '#6c757d'; ?>; font-size: 0.95rem; min-width: 45px;">
                                            <?php echo $iniciales; ?>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0 <?php echo ($estado != '1') ? 'text-decoration-line-through' : ''; ?>">
                                                <?php echo $nombreCompleto; ?>
                                            </h6>
                                            <small class="text-muted d-block">Código: <?php echo $codigo; ?></small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <?php if ($estado == '1'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small fw-bold" style="font-size: 0.75rem;">
                                                <i class="bi bi-check-circle-fill me-1"></i> Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1 small fw-bold" style="font-size: 0.75rem;">
                                                <i class="bi bi-x-circle-fill me-1"></i> Retirado
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted bg-white rounded-3 shadow-sm">
                            <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary"></i>
                            <h6 class="fw-bold mb-1 text-dark">No hay alumnos matriculados</h6>
                            <small class="text-muted">Aún no se registran estudiantes en esta sección.</small>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="modal-footer border-top-0 pt-0 bg-light rounded-bottom-3">
                    <a href="admin.view.php?tab=grados" class="btn btn-light shadow-sm text-muted w-100 fw-bold py-2">
                        Cerrar Directorio
                    </a>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>
<!-- Modal para matricular alumnos -->
<?php if ($mostrarModalMatricula): ?>
    <div class="modal fade show" id="modalMatricula" tabindex="-1" style="display:block; background:rgba(0,0,0,.5);" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow rounded-3">

                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4 bg-light rounded-top-3">
                    <div class="d-flex align-items-center w-100">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3 text-success">
                            <i class="bi bi-person-plus-fill fs-3"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-1">Matricular Alumnos</h5>
                            <p class="text-muted small mb-0">Sección: <strong class="text-dark"><?php echo htmlspecialchars($nombreGradoMatricula); ?></strong></p>
                        </div>
                        <a href="admin.view.php?tab=grados" class="btn-close ms-auto"></a>
                    </div>
                </div>

                <form action="admin.view.php?tab=grados" method="POST">
                    <div class="modal-body p-4 bg-light">
                        <input type="hidden" name="accion" value="procesar_matricula">
                        <input type="hidden" name="id_seccion" value="<?php echo htmlspecialchars($idSeccionMatricula); ?>">

                        <div class="alert alert-info border-0 shadow-sm small d-flex align-items-center">
                            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                            <div>
                                Solo se muestran los estudiantes activos que <strong>no están matriculados</strong> en ninguna otra sección.
                            </div>
                        </div>

                        <label class="form-label fw-bold text-muted small text-uppercase">Seleccione los estudiantes</label>

                        <div class="bg-white border rounded-3 p-0 shadow-sm" style="max-height: 350px; overflow-y: auto;">
                            <?php if (!empty($alumnosSinMatricula)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($alumnosSinMatricula as $alum): ?>
                                        <label class="list-group-item d-flex align-items-center gap-3 p-3 text-dark border-bottom" style="cursor: pointer; transition: background-color 0.2s;" onmouseover="this.classList.add('bg-light')" onmouseout="this.classList.remove('bg-light')">
                                            <input class="form-check-input flex-shrink-0 border-secondary" type="checkbox" name="alumnos_seleccionados[]" value="<?php echo htmlspecialchars($alum['id_alumno']); ?>" style="width: 1.3rem; height: 1.3rem;">
                                            <div>
                                                <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($alum['nombres'] . ' ' . $alum['apellidos']); ?></h6>
                                            </div>
                                        </label>

                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                                        <i class="bi bi-check2-all text-success fs-1"></i>
                                    </div>
                                    <h6 class="text-dark fw-bold mb-1">¡Todo en orden!</h6>
                                    <small class="text-muted">Todos los alumnos activos ya cuentan con un grado asignado.</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="modal-footer border-top-0 bg-light rounded-bottom-3 d-flex gap-2">
                        <a href="admin.view.php?tab=grados" class="btn btn-light shadow-sm text-muted fw-bold">Cancelar</a>
                        <button type="submit" class="btn shadow-sm px-4 fw-bold text-white" style="background-color: var(--verde-institucional);" <?php echo empty($alumnosSinMatricula) ? 'disabled' : ''; ?>>
                            <i class="bi bi-save-fill me-1"></i> Guardar Matrícula
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
<?php endif; ?>
<!-- Modal para editar grado (nombre, sección, aula, traslados) -->
<?php if ($mostrarModalEditarGrado): ?>
    <div class="modal fade show" id="modalEditarGrado" tabindex="-1" style="display:block; background:rgba(0,0,0,.5);" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow rounded-3">

                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4 bg-light rounded-top-3">
                    <div class="d-flex align-items-center w-100 mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 text-primary">
                            <i class="bi bi-gear-fill fs-3"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-1">Configuración del Grado</h5>
                            <p class="text-muted small mb-0"><?php echo htmlspecialchars($datosGradoEditar['grado_nombre'] . " " . $datosGradoEditar['seccion_nombre']); ?></p>
                        </div>
                        <a href="admin.view.php?tab=grados" class="btn-close ms-auto"></a>
                    </div>
                </div>
                <div class="bg-light px-4 border-bottom">
                    <ul class="nav nav-tabs border-0" id="configTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-pane" type="button" role="tab">Maestro Guía</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="traslado-tab" data-bs-toggle="tab" data-bs-target="#traslado-pane" type="button" role="tab">Trasladar Alumnos</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="retiro-tab" data-bs-toggle="tab" data-bs-target="#retiro-pane" type="button" role="tab">Retirar Alumnos</button>
                        </li>
                    </ul>
                </div>

                <div class="modal-body p-4 bg-white">
                    <div class="tab-content" id="configTabsContent">
                        <!-- Pestaña de Asignacion de Maestro Guia -->
                        <div class="tab-pane fade show active" id="info-pane" role="tabpanel">
                            <form action="admin.view.php?tab=grados" method="POST">
                                <input type="hidden" name="accion" value="actualizar_maestro_guia">
                                <input type="hidden" name="id_seccion" value="<?php echo $datosGradoEditar['id_seccion']; ?>">

                                <div class="mb-4 mt-3">
                                    <label class="form-label text-muted small fw-bold text-uppercase d-block mb-2">
                                        Maestro Guía Asignado
                                    </label>
                                    <div class="input-group shadow-sm rounded-3">
                                        <span class="input-group-text bg-light border-end-0 text-muted">
                                            <i class="bi bi-person-badge"></i>
                                        </span>
                                        <select name="id_profesor" class="form-select border-start-0 bg-light p-2" required>
                                            <option value="">-- Sin maestro asignado --</option>

                                            <?php if (!empty($listaProfesores)): ?>
                                                <?php foreach ($listaProfesores as $prof):
                                                    // Si el id de este profesor de la lista es igual al que ya tiene la sección, lo marcamos como seleccionado
                                                    $selected = ($prof['id_profesor'] === ($datosGradoEditar['id_profesor_actual'] ?? '')) ? 'selected' : '';
                                                ?>
                                                    <option value="<?php echo htmlspecialchars($prof['id_profesor']); ?>" <?php echo $selected; ?>>
                                                        <?php echo htmlspecialchars($prof['nombre_completo']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                        </select>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        Selecciona al profesor responsable. Un docente puede ser guía de múltiples secciones si es necesario.
                                    </small>
                                </div>

                                <div class="text-end pt-3 border-top">
                                    <button type="submit" class="btn text-white shadow-sm fw-bold px-4" style="background-color: var(--verde-institucional);">
                                        <i class="bi bi-save-fill me-1"></i> Asignar Maestro
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- Pestaña de Traslado de Estudiante -->
                        <div class="tab-pane fade" id="traslado-pane" role="tabpanel">
                            <form action="admin.view.php?tab=grados" method="POST">
                                <input type="hidden" name="accion" value="trasladar_alumnos">
                                <input type="hidden" name="id_seccion_origen" value="<?php echo $datosGradoEditar['id_seccion']; ?>">

                                <div class="alert alert-warning border-0 shadow-sm small d-flex align-items-center mb-3">
                                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                    <div>Seleccione los alumnos que desea retirar de esta sección para moverlos a un nuevo grado.</div>
                                </div>

                                <div class="border rounded-3 p-0 shadow-sm mb-4" style="max-height: 250px; overflow-y: auto;">
                                    <?php if (!empty($alumnosDelGradoEditar)): ?>
                                        <div class="list-group list-group-flush">
                                            <?php foreach ($alumnosDelGradoEditar as $alum): ?>
                                                <label class="list-group-item d-flex align-items-center gap-3 p-2 text-dark border-bottom" style="cursor: pointer;">
                                                    <input class="form-check-input flex-shrink-0" type="checkbox" name="alumnos_traslado[]" value="<?php echo htmlspecialchars($alum['id_alumno']); ?>">
                                                    <div>
                                                        <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;"><?php echo htmlspecialchars($alum['nombres'] . ' ' . $alum['apellidos']); ?></h6>
                                                    </div>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-4 text-muted small">No hay alumnos para trasladar.</div>
                                    <?php endif; ?>
                                </div>

                                <div class="row align-items-end bg-light p-3 rounded-3 border">
                                    <div class="col-md-8">
                                        <label class="form-label text-muted small fw-bold">Mover a la sección:</label>
                                        <select name="id_seccion_destino" class="form-select border-primary" required>
                                            <option value="">Seleccione el grado destino...</option>
                                            <?php
                                            if (!empty($listaGrados)):
                                                foreach ($listaGrados as $g_dest):
                                                    if (!empty($g_dest['id_seccion']) && $g_dest['id_seccion'] != $datosGradoEditar['id_seccion']):
                                            ?>
                                                        <option value="<?php echo htmlspecialchars($g_dest['id_seccion']); ?>">
                                                            <?php echo htmlspecialchars($g_dest['nombre_grad'] . ' ' . $g_dest['nombre_sec']); ?>
                                                        </option>
                                            <?php
                                                    endif;
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <button type="submit" class="btn btn-warning shadow-sm fw-bold w-100"><i class="bi bi-arrow-left-right me-1"></i> Trasladar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Pestaña de Retiro de Estudiante -->
                        <div class="tab-pane fade" id="retiro-pane" role="tabpanel">
                            <form action="admin.view.php?tab=grados" method="POST">
                                <input type="hidden" name="accion" value="retirar_alumnos_seccion">
                                <input type="hidden" name="id_seccion_origen" value="<?php echo $datosGradoEditar['id_seccion']; ?>">

                                <div class="alert alert-danger border-0 shadow-sm small d-flex align-items-center mb-3">
                                    <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
                                    <div>
                                        Seleccione los alumnos que desea <strong>remover de esta sección</strong>. El alumno quedará libre para ser matriculado en otro grado.
                                    </div>
                                </div>

                                <div class="border rounded-3 p-0 shadow-sm mb-4" style="max-height: 250px; overflow-y: auto;">
                                    <?php if (!empty($alumnosDelGradoEditar)): ?>
                                        <div class="list-group list-group-flush">
                                            <?php foreach ($alumnosDelGradoEditar as $alum): ?>
                                                <label class="list-group-item d-flex align-items-center gap-3 p-2 text-dark border-bottom" style="cursor: pointer;">
                                                    <input class="form-check-input flex-shrink-0" type="checkbox" name="alumnos_retiro[]" value="<?php echo htmlspecialchars($alum['id_alumno']); ?>">
                                                    <div>
                                                        <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;"><?php echo htmlspecialchars($alum['nombres'] . ' ' . $alum['apellidos']); ?></h6>
                                                    </div>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-4 text-muted small">No hay alumnos inscritos en esta sección para retirar.</div>
                                    <?php endif; ?>
                                </div>

                                <div class="text-end pt-3 border-top">
                                    <button type="submit" class="btn btn-danger shadow-sm fw-bold px-4" <?php echo empty($alumnosDelGradoEditar) ? 'disabled' : ''; ?>>
                                        <i class="bi bi-person-dash-fill me-1"></i> Confirmar Retiro de Alumnos
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light rounded-bottom-3">
                    <a href="admin.view.php?tab=grados" class="btn btn-light shadow-sm text-muted fw-bold w-100 py-2">Cerrar Panel</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<!-- Modal para añadir un Grado -->
<div class="modal fade" id="modalNuevoGrado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">

            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4 bg-light rounded-top-3">
                <div class="d-flex align-items-center w-100">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3 text-warning">
                        <i class="bi bi-journal-plus fs-3"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1">Crear Nueva Sección</h5>
                        <p class="text-muted small mb-0">Añade un grupo de estudio al catálogo académico</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <form action="admin.view.php?tab=grados" method="POST">
                <div class="modal-body p-4 bg-light">
                    <input type="hidden" name="accion" value="guardar_nuevo_grado">

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Nivel Académico / Grado</label>
                        <select name="id_grado" class="form-select border-2 p-2" required>
                            <option value="">-- Seleccione el Grado --</option>
                            <?php
                            // Obtenemos los grados agrupados para que no se repitan en el select
                            // Tu controlador ya genera una lista base en la consulta principal
                            $gradosUnicos = [];
                            foreach ($listaGrados as $g) {
                                if (!in_array($g['id_grado'], array_column($gradosUnicos, 'id_grado'))) {
                                    $gradosUnicos[] = $g;
                                }
                            }
                            foreach ($gradosUnicos as $grad):
                            ?>
                                <option value="<?php echo $grad['id_grado']; ?>">
                                    <?php echo htmlspecialchars($grad['nombre_grad'] . ' (' . $grad['modalidad'] . ' - ' . $grad['turno'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-muted small fw-bold text-uppercase">Letra / Identificador de Sección</label>
                        <input type="text" name="nombre_sec" class="form-control border-2 p-2" placeholder="Ej: A, B, C o Única" maxlength="20" required>
                        <div class="form-text text-muted small mt-1">
                            El sistema validará automáticamente que esta sección no exista previamente en el mismo nivel.
                        </div>
                    </div>

                </div>

                <div class="modal-footer border-top-0 bg-light rounded-bottom-3 d-flex gap-2">
                    <button type="button" class="btn btn-light shadow-sm text-muted fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning shadow-sm px-4 fw-bold text-dark">
                        <i class="bi bi-plus-circle-fill me-1"></i> Crear Sección
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
</div>