<?php include_once __DIR__ . "/../controllers/login_docente.controller.php"; ?>
<!-- Panel Principal del Docente -->
<div class="tab-pane fade <?php echo ($active == 'paneldocente') ? 'show active' : ''; ?>" id="vista-panel">
    <h2 class="fw-bold text-dark mb-4">Mi Panel</h2>
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #198754 !important;">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.8rem;">Mis Asignaturas</h6>
                        <h2 class="mb-0 fw-bold text-dark"><?php echo $totalAsignaturas ?? 0; ?></h2>
                    </div>
                    <div class="bg-light rounded-circle p-3" style="color: #198754;">
                        <i class="bi bi-journal-bookmark-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Agrega más tarjetas según lo que necesites mostrarle al docente -->
    </div>
</div>