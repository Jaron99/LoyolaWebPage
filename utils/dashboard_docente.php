<?php
require_once __DIR__ . "/../models/configuracion.model.php";

// Obtenemos los ajustes globales del sistema
$configModel = new Configuracion(); 
$ajustes = $configModel->obtenerAjustes();

// Variables de Configuración
$ano_lectivo = $ajustes['ano_lectivo'] ?? date('Y');
$parciales = [
    'I Parcial'   => isset($ajustes['editar_i_parcial']) && $ajustes['editar_i_parcial'],
    'II Parcial'  => isset($ajustes['editar_ii_parcial']) && $ajustes['editar_ii_parcial'],
    'III Parcial' => isset($ajustes['editar_iii_parcial']) && $ajustes['editar_iii_parcial'],
    'IV Parcial'  => isset($ajustes['editar_iv_parcial']) && $ajustes['editar_iv_parcial']
];

// Nos aseguramos que las variables del controlador existan por seguridad
$total_alumnos = $total_alumnos ?? 0;
$total_asignaturas = $total_asignaturas ?? 0;
$total_secciones = $total_secciones ?? 0;
?>

<div class="tab-pane fade <?php echo (!isset($_GET['tab']) || $_GET['tab'] === 'paneldocente' || $_GET['tab'] === '') ? 'show active' : ''; ?>" id="vista-dashboard">
    
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">¡Bienvenido, Profesor(a) <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h2>
        <p class="text-muted">Este es su resumen académico para el ciclo escolar actual.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; border-bottom: 5px solid #0dcaf0 !important;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-3 me-3">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.8rem;">Total Alumnos</h6>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $total_alumnos; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; border-bottom: 5px solid #6f42c1 !important;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-purple bg-opacity-10 text-purple rounded-3 p-3 me-3" style="color: #6f42c1; background-color: rgba(111, 66, 193, 0.1);">
                        <i class="bi bi-journal-bookmark-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.8rem;">Mis Asignaturas</h6>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $total_asignaturas; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; border-bottom: 5px solid #fd7e14 !important;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-orange bg-opacity-10 text-orange rounded-3 p-3 me-3" style="color: #fd7e14; background-color: rgba(253, 126, 20, 0.1);">
                        <i class="bi bi-door-open-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.8rem;">Secciones a Cargo</h6>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $total_secciones; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; border-bottom: 5px solid #ffc107 !important;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                        <i class="bi bi-calendar-event-fill fs-3 text-dark"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.8rem;">Año Académico</h6>
                        <h3 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($ano_lectivo); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-shield-lock text-primary me-2"></i> Estado de Cortes Evaluativos</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-4">
                        A continuación se muestran los períodos de evaluación activos. Solo podrá ingresar o modificar calificaciones en los cortes que se encuentren "Abiertos".
                    </p>
                    
                    <div class="row g-3">
                        <?php foreach($parciales as $nombre => $esta_abierto): ?>
                            <div class="col-6 col-md-3">
                                <div class="p-3 border rounded-3 text-center <?php echo $esta_abierto ? 'bg-success bg-opacity-10 border-success' : 'bg-light'; ?>">
                                    <?php if($esta_abierto): ?>
                                        <i class="bi bi-unlock-fill text-success fs-2 mb-2 d-block"></i>
                                        <h6 class="fw-bold text-success mb-1"><?php echo $nombre; ?></h6>
                                        <span class="badge bg-success rounded-pill">Abierto</span>
                                    <?php else: ?>
                                        <i class="bi bi-lock-fill text-muted fs-2 mb-2 d-block"></i>
                                        <h6 class="fw-bold text-muted mb-1"><?php echo $nombre; ?></h6>
                                        <span class="badge bg-secondary rounded-pill">Cerrado</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-lightning-charge text-warning me-2"></i> Accesos Rápidos</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <a href="?tab=calificaciones" class="btn btn-outline-primary text-start p-3 fw-bold rounded-3">
                            <i class="bi bi-journal-check fs-4 me-3 align-middle"></i> Evaluar Calificaciones
                        </a>
                        
                        <a href="#" class="btn btn-outline-success text-start p-3 fw-bold rounded-3">
                            <i class="bi bi-file-earmark-pdf fs-4 me-3 align-middle"></i> Imprimir Reportes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>