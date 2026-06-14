<div class="sidebar d-flex flex-column py-3" id="sidebar">
    <div class="text-center mb-4 mt-2">
        <img src="../Imagenes/Logo.png" alt="Logo" class="sidebar-logo mb-2">
        <h5 class="fw-bold mb-0 text-white">Portal San Ignacio</h5>
        <small class="text-white-50">
            <?php 
            // Subtítulo dinámico dependiendo del rol
            if ($_SESSION['rol'] == 'admin') echo 'Administración General';
            elseif ($_SESSION['rol'] == 'docente') echo 'Portal Docente';
            else echo 'Portal Estudiantil';
            ?>
        </small>
    </div>

    <ul class="nav nav-pills flex-column mb-auto mt-2">
        
        <?php if ($_SESSION['rol'] == 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'panel') ? 'active' : ''; ?>" href="admin.view.php?tab=panel">
                    <i class="bi bi-speedometer2 me-3"></i> Panel Principal
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'usuarios') ? 'active' : ''; ?>" href="admin.view.php?tab=usuarios">
                    <i class="bi bi-people-fill me-3"></i> Gestión de Usuarios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'grados') ? 'active' : ''; ?>" href="admin.view.php?tab=grados">
                    <i class="bi bi-diagram-3-fill me-3"></i> Grados y Secciones
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'respaldo') ? 'active' : ''; ?>" href="admin.view.php?tab=respaldo">
                    <i class="bi bi-cloud-upload-fill me-3"></i> Respaldo del Sistema
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'configuracion') ? 'active' : ''; ?>" href="admin.view.php?tab=configuracion">
                    <i class="bi bi-gear-fill me-3"></i> Configuración
                </a>
            </li>

        <?php elseif ($_SESSION['rol'] == 'docente'): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'panel') ? 'active' : ''; ?>" href="docente.view.php?tab=panel">
                    <i class="bi bi-easel-fill me-3"></i> Mi Panel
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'calificaciones') ? 'active' : ''; ?>" href="docente.view.php?tab=calificaciones">
                    <i class="bi bi-journal-check me-3"></i> Ingreso de Notas
                </a>
            </li>

        <?php elseif ($_SESSION['rol'] == 'estudiante'): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'panel') ? 'active' : ''; ?>" href="estudiante.view.php?tab=panel">
                    <i class="bi bi-person-badge-fill me-3"></i> Mi Perfil
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'boletin') ? 'active' : ''; ?>" href="estudiante.view.php?tab=boletin">
                    <i class="bi bi-file-earmark-text-fill me-3"></i> Mi Boletín
                </a>
            </li>

        <?php endif; ?>

    </ul>
</div>