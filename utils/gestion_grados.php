<?php include_once __DIR__ . "/../controllers/admin.controller.php"; ?>
<!-- Grados y Secciones -->
<div class="tab-pane fade <?php echo ($active == 'grados') ? 'show active' : ''; ?>" id="vista-grados">
    <!-- Título y Descripción -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">Grados y Secciones</h2>
            <p class="text-muted mb-0">Gestione la estructura académica, turnos y asignaturas.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success shadow-sm">
                <i class="bi bi-journal-plus me-2"></i> Nueva Asignatura
            </button>
            <button class="btn btn-amarillo-institucional shadow-sm text-dark fw-semibold">
                <i class="bi bi-plus-circle-fill me-2"></i> Nuevo Grado
            </button>
        </div>
    </div>
    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4 bg-white">
        <div class="card-body p-3">
            <form method="GET" action="../views/admin.view.php">
                <input type="hidden" name="tab" value="grados">
                <div class="row g-2">
                    <div class="col-md-4">
                        <select name="nivel" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">Todas las Modalidades</option>
                            <?php
                            if (!empty($nivelesAcademicos)):
                                foreach ($nivelesAcademicos as $modalidad):
                                    $seleccionado = ($filtronivel == $modalidad) ? 'selected' : '';
                            ?>
                                    <option value="<?php echo $modalidad; ?>" <?php echo $seleccionado; ?>>
                                        <?php echo $modalidad; ?>
                                    </option>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-light text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="busqueda" class="form-control border-0 bg-light" placeholder="Buscar grado o sección..." value="<?php echo htmlspecialchars($busqueda); ?>">
                            <button type="submit" class="btn text-white px-4 shadow-sm" style="background-color: #198754;">Buscar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Tabla de Grados y Secciones -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
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
                                        <span class="text-muted fst-italic small"><i class="bi bi-person-x me-1"></i> Sin asignar</span>
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
                                        <button class="btn btn-outline-success btn-sm " title="Matricular alumnos" data-bs-toggle="modal" data-bs-target="#modalMatricularAlumnos">
                                            <i class="bi bi-person-plus"></i>
                                        </button>
                                        <!-- Botón de Editar Grado -->
                                        <button class="btn btn-outline-primary btn-sm shadow-sm" title="Editar grado" data-bs-toggle="modal" data-bs-target="#modalEditarGrado">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <!-- Botón de Eliminar Grado -->
                                        <button class="btn btn-outline-danger btn-sm shadow-sm" title="Eliminar grado" data-bs-toggle="modal" data-bs-target="#modalEliminarGrado">
                                            <i class="bi bi-trash"></i>
                                        </button>
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
</div>