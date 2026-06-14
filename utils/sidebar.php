<?php
// Salvaguardas MVC para evitar errores de variables indefinidas
if (!isset($active)) {
    $active = isset($_GET['tab']) ? $_GET['tab'] : 'panel';
}
if (!isset($_SESSION['rol'])) {
    $_SESSION['rol'] = '';
}
?>

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
            <?php
            $menuUsuariosAbierto = ($active === 'estudiantes' || $active === 'docentes') ? 'show' : '';
            $iconoActivo = ($active === 'estudiantes' || $active === 'docentes') ? 'text-warning' : 'text-white';
            ?>
            <li class="nav-item w-100 mb-2">
                <a href="#submenuUsuarios" data-bs-toggle="collapse" class="nav-link text-white d-flex align-items-center justify-content-between transition-all" aria-expanded="<?php echo !empty($menuUsuariosAbierto) ? 'true' : 'false'; ?>">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-building fs-5 <?php echo $iconoActivo; ?>"></i>
                        <span class="d-none d-md-inline fw-semibold">Gestión Academica</span>
                    </div>
                    <i class="bi bi-chevron-down d-none d-md-inline text-white-50" style="font-size: 0.8rem;"></i>
                </a>
                <div class="collapse <?php echo $menuUsuariosAbierto; ?>" id="submenuUsuarios">
                    <ul class="nav flex-column ms-4 mt-2 border-start border-light border-opacity-25" style="border-width: 2px !important;">

                        <li class="nav-item mb-1">
                            <a href="admin.view.php?tab=estudiantes" class="nav-link d-flex align-items-center gap-3 py-2 <?php echo ($active === 'estudiantes') ? 'text-warning fw-bold' : 'text-white text-opacity-75'; ?>" style="font-size: 0.9rem;">
                                <i class="bi bi-mortarboard-fill"></i>
                                <span class="d-none d-md-inline">Estudiantes</span>
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a href="admin.view.php?tab=docentes" class="nav-link d-flex align-items-center gap-3 py-2 <?php echo ($active === 'docentes') ? 'text-warning fw-bold' : 'text-white text-opacity-75'; ?>" style="font-size: 0.9rem;">
                                <i class="bi bi-person-badge-fill"></i>
                                <span class="d-none d-md-inline">Docentes</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'calificaciones') ? 'active' : ''; ?>" href="admin.view.php?tab=calificaciones">
                    <i class="bi bi-journal-check me-3"></i> Calificaciones
                </a>
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