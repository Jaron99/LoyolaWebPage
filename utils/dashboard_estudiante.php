<div class="tab-pane fade <?php echo (!isset($_GET['tab']) || $_GET['tab'] === 'panelestudiante' || $_GET['tab'] === '') ? 'show active' : ''; ?>" id="vista-dashboard">

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">¡Hola, <?php echo htmlspecialchars(!empty($perfil['nombres']) ? $perfil['nombres'] : $_SESSION['usuario']); ?>!</h2>
        <p class="text-muted">Consulta tu rendimiento académico para el Ciclo Escolar <?php echo htmlspecialchars($ano_lectivo); ?>.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; border-left: 5px solid #198754 !important;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                        <i class="bi bi-mortarboard-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.75rem;">Grado y Sección</h6>
                        <h5 class="fw-bold text-dark mb-0">
                            <?php echo htmlspecialchars((!empty($perfil['nombre_grad']) ? $perfil['nombre_grad'] : 'No Asignado') . ' "' . (!empty($perfil['nombre_sec']) ? $perfil['nombre_sec'] : '-') . '"'); ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; border-left: 5px solid #ffc107 !important;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                        <i class="bi bi-card-text fs-3 text-dark"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.75rem;">Código MINED</h6>
                        <h5 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars(!empty($perfil['cod_mined']) ? $perfil['cod_mined'] : 'N/A'); ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; border-left: 5px solid #0dcaf0 !important;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-3 me-3">
                        <i class="bi bi-telephone-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.75rem;">Contacto de Tutor</h6>
                        <h5 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars(!empty($perfil['telefono']) ? $perfil['telefono'] : 'N/A'); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white border-bottom p-4">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-journal-check text-success me-2"></i> Mis Calificaciones Oficiales</h5>
        </div>
        <div class="card-body p-4">

            <?php if ($notas_publicadas): ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle tabla-datatable w-100">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-bold">Asignatura</th>
                                <th class="text-center fw-bold">I Corte</th>
                                <th class="text-center fw-bold">II Corte</th>
                                <th class="text-center fw-bold">III Corte</th>
                                <th class="text-center fw-bold">IV Corte</th>
                                <th class="text-center fw-bold">Promedio</th>
                                <th class="text-center fw-bold">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($mis_notas)): ?>
                                <?php while ($row = $mis_notas->fetch_assoc()): ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['nombre_asig']); ?></td>
                                        <td class="text-center"><?php echo $row['corte1'] ?? '-'; ?></td>
                                        <td class="text-center"><?php echo $row['corte2'] ?? '-'; ?></td>
                                        <td class="text-center"><?php echo $row['corte3'] ?? '-'; ?></td>
                                        <td class="text-center"><?php echo $row['corte4'] ?? '-'; ?></td>
                                        <td class="text-center fw-bold text-primary"><?php echo $row['promedio'] ?? '-'; ?></td>
                                        <td class="text-center">
                                            <?php if ($row['estado'] === 'Aprobado'): ?>
                                                <span class="badge rounded-pill bg-success">Aprobado</span>
                                            <?php elseif ($row['estado'] === 'Reprobado'): ?>
                                                <span class="badge rounded-pill bg-danger">Reprobado</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary rounded-pill">En Curso</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>

                <div class="text-center py-5">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-lock-fill fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Calificaciones en Proceso de Evaluación</h5>
                    <p class="text-muted small mx-auto" style="max-width: 500px;">
                        La dirección académica se encuentra actualmente revisando y validando los registros de notas de este periodo. Las calificaciones se publicarán de forma automática una vez concluido el proceso.
                    </p>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>