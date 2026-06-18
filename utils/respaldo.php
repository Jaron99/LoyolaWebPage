<?php 
// Solicitamos el historial directamente para pintarlo en la vista
require_once __DIR__ . "/../models/respaldo.model.php";
$modeloResp = new Respaldo();
$historial = $modeloResp->obtenerHistorial();
?>

<div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'respaldo') ? 'show active' : ''; ?>" id="vista-respaldo">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">Respaldo del Sistema</h2>
            <p class="text-muted mb-0">Genere copias de seguridad de la base de datos o restaure información previa.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 5px solid var(--verde-institucional);">
                <form action="../controllers/respaldo.controller.php" method="POST" class="card-body p-4 text-center d-flex flex-column m-0">
                    <input type="hidden" name="action" value="backup">
                    
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-cloud-arrow-down-fill fs-1 text-success"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">Crear Copia de Seguridad</h4>
                    <p class="text-muted small mb-4 flex-grow-1">Descarga un archivo <strong>.sql</strong> con toda la información actual de estudiantes, docentes, matrículas y calificaciones. Se recomienda realizar esta acción de forma semanal.</p>

                    <button type="submit" class="btn btn-primary px-4 py-3 w-100 shadow-sm rounded-3 fw-bold" style="background-color: var(--amarillo-institucional); border-color: var(--amarillo-institucional); color: #000;">
                        <i class="bi bi-download me-2"></i> Descargar Respaldo Ahora
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 5px solid #dc3545;">
                <form action="../controllers/respaldo.controller.php" method="POST" enctype="multipart/form-data" class="card-body p-4 text-center d-flex flex-column m-0">
                    <input type="hidden" name="action" value="restore">
                    
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-cloud-arrow-up-fill fs-1 text-danger"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">Restaurar Sistema</h4>
                    <p class="text-muted small mb-4 flex-grow-1">Sube un archivo de respaldo previo para restaurar el sistema. <br><strong class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> Advertencia:</strong> Esto sobreescribirá todos los datos actuales.</p>

                    <div class="input-group mb-3 text-start shadow-sm rounded-3 overflow-hidden">
                        <input type="file" name="archivo_sql" class="form-control bg-light border-0 py-2" id="archivoSql" accept=".sql" required>
                    </div>

                    <button type="submit" class="btn btn-outline-danger px-4 py-2 w-100 shadow-sm rounded-3 fw-bold" onclick="return confirm('¿Está seguro? Esta acción borrará la información actual y la reemplazará por la del archivo.');">
                        <i class="bi bi-upload me-2"></i> Subir y Restaurar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4 bg-white">
        <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-muted"></i> Últimos Movimientos</h5>
        </div>
        <div class="card-body p-4 pt-2">
            <ul class="list-group list-group-flush border-0">
                
                <?php if (empty($historial)): ?>
                    <li class="list-group-item text-center text-muted py-4 border-0">No hay respaldos generados todavía.</li>
                <?php else: ?>
                    <?php foreach ($historial as $item): 
                        // Calculamos cuántos días han pasado
                        $fecha_db = new DateTime($item['fecha_accion']);
                        $hoy = new DateTime();
                        $diferencia = $hoy->diff($fecha_db)->days;
                        $texto_dias = ($diferencia == 0) ? "Hoy" : "Hace $diferencia días";
                        
                        // Cambiamos el icono dependiendo si fue Descarga (Backup) o Subida (Restore)
                        $icono = ($item['tipo_accion'] == 'BACKUP') ? 'bi-file-earmark-code-fill text-success' : 'bi-arrow-counterclockwise text-danger';
                    ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-bottom bg-transparent">
                            <div>
                                <i class="bi <?php echo $icono; ?> me-3 fs-5"></i>
                                <span class="fw-semibold text-dark"><?php echo htmlspecialchars($item['nombre_archivo']); ?></span>
                                <small class="text-muted ms-2 d-block d-md-inline">(<?php echo $item['tipo_accion']; ?>)</small>
                            </div>
                            <span class="badge bg-light text-muted rounded-pill px-3 py-2 border"><?php echo $texto_dias; ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</div>