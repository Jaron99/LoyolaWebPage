<?php include_once __DIR__ . "/../controllers/admin.controller.php"; ?>
<!-- Panel Principal -->
<div class="tab-pane fade <?php echo ($active == 'panel') ? 'show active' : ''; ?>" id="vista-panel">
    <h2 class="fw-bold text-dark mb-4">Panel Principal</h2>
    <div class="row">
        <!-- // Tarjetas de Resumen -->
        <div class="col-lg-4 mb-4">
            <div class="d-flex flex-column gap-3 h-100">
                <!-- Tarjeta de Total Alumnos -->
                <div class="card border-0 shadow-sm flex-fill" style="border-left: 5px solid #ffc107 !important;">
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Total Alumnos</h6>
                                <h2 class="mb-0 fw-bold text-dark"><?php echo $resumenDashboard['total_alumnos']; ?></h2>
                            </div>
                            <div class="bg-light rounded-circle p-3" style="color: #ffc107;">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta de Total Docentes -->
                <div class="card border-0 shadow-sm flex-fill" style="border-left: 5px solid #198754 !important;">
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Total Docentes</h6>
                                <h2 class="mb-0 fw-bold text-dark"><?php echo $resumenDashboard['total_docentes']; ?></h2>
                            </div>
                            <div class="bg-light rounded-circle p-3" style="color: #198754;">
                                <i class="bi bi-person-badge-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta de Periodo Actual -->
                <div class="card border-0 shadow-sm flex-fill text-white" style="background-color: #198754;">
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase mb-1 text-light" style="font-size: 0.8rem;">Periodo Actual</h6>
                                <h3 class="mb-0 fw-bold"><?php echo $resumenDashboard['periodo_actual']; ?></h3>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: rgba(255,255,255,0.2);">
                                <i class="bi bi-calendar3 fs-3 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Accesos Rápidos -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-lightning-charge-fill text-warning me-2"></i> Accesos Rápidos</h5>
                    <div class="d-flex flex-column gap-3">
                        <!-- Botón de Matricular Nuevo Alumno -->
                        <button class="btn text-start p-3 shadow-sm border-0 bg-white opacity-50" style="cursor: not-allowed;" disabled>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 me-4" style="background-color: rgba(108, 117, 125, 0.1); color: #6c757d;">
                                    <i class="bi bi-person-plus fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="fw-bold text-secondary mb-1">Matricular Nuevo Alumno</h5>
                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">Próximamente</span>
                                    </div>
                                    <small class="text-muted">Inscribir a un estudiante en un grado y sección para este año escolar.</small>
                                </div>
                            </div>
                        </button>
                        <!-- Botón de Registrar Nuevo Docente -->
                        <button class="btn text-start p-3 shadow-sm border-0 bg-white opacity-50" style="cursor: not-allowed;" disabled>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 me-4" style="background-color: rgba(108, 117, 125, 0.1); color: #6c757d;">
                                    <i class="bi bi-person-badge fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="fw-bold text-secondary mb-1">Registrar Nuevo Docente</h5>
                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">Próximamente</span>
                                    </div>
                                    <small class="text-muted">Añadir personal al sistema y asignarles sus respectivas materias.</small>
                                </div>
                            </div>
                        </button>
                        <!-- Boton de Reporte de Calificaciones -->
                        <button class="btn text-start p-3 shadow-sm border-0 bg-white opacity-50" style="cursor: not-allowed;" disabled>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 me-4" style="background-color: rgba(108, 117, 125, 0.1); color: #6c757d;">
                                    <i class="bi bi-file-earmark-spreadsheet fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="fw-bold text-secondary mb-1">Reporte de Calificaciones</h5>
                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">Próximamente</span>
                                    </div>
                                    <small class="text-muted">Exportar boletines o auditar notas ingresadas por los profesores.</small>
                                </div>
                            </div>
                        </button>
                        <!-- Botón de Copia de Seguridad -->
                        <a href="admin.view.php?tab=respaldo" class="text-decoration-none">
                            <button class="btn btn-white text-start p-3 shadow-sm border-0 bg-white w-100" style="transition: all 0.3s;">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle p-3 me-4" style="background-color: rgba(25, 135, 84, 0.1); color: #198754;">
                                        <i class="bi bi-database-down fs-3"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1">Copia de Seguridad</h5>
                                        <small class="text-muted">Ir al panel para generar y descargar un respaldo completo de la base de datos.</small>
                                    </div>
                                </div>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>