<nav class="navbar navbar-expand-lg topbar px-4 py-3 sticky-top">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-secondary d-md-none me-3" type="button" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h5 class="mb-0 text-verde-institucional fw-bold d-none d-md-block">Dirección Académica</h5>
        </div>
        <div class="d-flex align-items-center">
            <div class="text-end me-3 d-none d-sm-block">
                <div class="fw-bold text-dark" style="line-height: 1.2;">
                    <?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : 'Usuario'; ?>
                </div>
                <!-- Rol en el sistema con Mayuscula Inicial -->
                <small class="text-muted "><?php echo isset($_SESSION['rol']) ? ucfirst(strtolower(htmlspecialchars($_SESSION['rol']))) : ''; ?></small>
            </div>
            <div class="dropdown">
                <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-2 text-verde-institucional"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i> Mi Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2 text-danger fw-bold" href="../controllers/logout.controller.php"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>