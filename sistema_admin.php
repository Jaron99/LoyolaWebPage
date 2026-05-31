<?php
// Aseguramos que el usuario tenga rol de admin para acceder a esta página
session_start();
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: login.php");
    exit();
}

// Incluimos el modelo de administración para interactuar con la base de datos
include_once "Models/admin.model.php";
$adminModel = new Admin();

// Obtenemos los datos necesarios para mostrar en el dashboard y otras vistas
$usuarios = $adminModel->getUsuarios();
$resumenDashboard = $adminModel->obtenerResumenDashboard();
$nivelesAcademicos = $adminModel->obtenerNivelesAcademicos();

// Determinamos qué pestaña está activa según el parámetro GET 'tab'
$active = isset($_GET['tab']) ? $_GET['tab'] : 'panel';
$filtronivel = isset($_GET['nivel']) ? $_GET['nivel'] : "";
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : "";

// Obtenemos la lista de grados y secciones según los filtros aplicados
$listaGrados = $adminModel->obtenerGradosSeccion($filtronivel, $busqueda);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal San Ignacio - Panel de Administración</title>
    <link rel="icon" type="image/png" href="Imagenes/Logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Estilos Personalizados -->
    <style>
        :root {
            --verde-institucional: #006a28;
            --amarillo-institucional: #ffd709;
        }

        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* --- BARRA LATERAL (SIDEBAR) --- */
        .sidebar {
            background-color: var(--verde-institucional);
            min-height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease-in-out;
        }

        .sidebar-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            background-color: white;
            border-radius: 50%;
            padding: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .nav-pills .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 0 25px 25px 0;
            margin-right: 15px;
        }

        .nav-pills .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-pills .nav-link.active {
            background-color: var(--amarillo-institucional);
            color: #000;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            transition: all 0.3s ease-in-out;
        }

        .topbar {
            background-color: white;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .text-verde-institucional {
            color: var(--verde-institucional) !important;
        }

        .btn-amarillo-institucional {
            background-color: var(--amarillo-institucional);
            border-color: var(--amarillo-institucional);
            color: #000;
            font-weight: bold;
            transition: all 0.2s;
        }

        .btn-amarillo-institucional:hover {
            background-color: #e6c208;
            border-color: #e6c208;
            color: #000;
            transform: translateY(-2px);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--amarillo-institucional);
            box-shadow: 0 0 0 0.25rem rgba(255, 215, 9, 0.25);
        }

        /* Insignias (Badges) de estado */
        .badge-estudiante {
            background-color: #0d6efd;
            color: white;
        }

        .badge-profesor {
            background-color: var(--verde-institucional);
            color: white;
        }

        .badge-admin {
            background-color: #212529;
            color: white;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -260px;
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Barra Lateral -->
    <div class="sidebar d-flex flex-column py-3" id="sidebar">
        <!-- Logo y Titulo -->
        <div class="text-center mb-4 mt-2">
            <img src="Imagenes/Logo.png" alt="Logo" class="sidebar-logo mb-2">
            <h5 class="fw-bold mb-0 text-white">Portal San Ignacio</h5>
            <small class="text-white-50">Administración General</small>
        </div>

        <!-- Opciones de Navegación -->
        <ul class="nav nav-pills flex-column mb-auto mt-2">
            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'panel') ? 'active' : ''; ?>" href="sistema_admin.php?tab=panel">
                    <i class="bi bi-speedometer2 me-3"></i> Panel Principal
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'usuarios') ? 'active' : ''; ?>" href="sistema_admin.php?tab=usuarios">
                    <i class="bi bi-people-fill me-3"></i> Gestión de Usuarios
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'grados') ? 'active' : ''; ?>" href="sistema_admin.php?tab=grados">
                    <i class="bi bi-diagram-3-fill me-3"></i> Grados y Secciones
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'respaldo') ? 'active' : ''; ?>" href="sistema_admin.php?tab=respaldo">
                    <i class="bi bi-cloud-upload-fill me-3"></i> Respaldo del Sistema
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo ($active == 'configuracion') ? 'active' : ''; ?>" href="sistema_admin.php?tab=configuracion">
                    <i class="bi bi-gear-fill me-3"></i> Configuración del Sistema
                </a>
            </li>
        </ul>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content" id="main-content">
        <!-- Barra de Navegación -->
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
                        <div class="fw-bold text-dark" style="line-height: 1.2;">Admin</div>
                        <small class="text-muted">Administrador del Sistema</small>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-2 text-verde-institucional"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i> Mi Perfil</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item py-2 text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenido de las Vistas -->
        <div class="container-fluid p-4">

            <div class="tab-content" id="v-pills-tabContent">
                <!-- Panel Principal -->
                <div class="tab-pane fade <?php echo ($active == 'panel') ? 'show active' : ''; ?>" id="vista-panel">
                    <h2 class="fw-bold text-dark mb-4">Panel Principal</h2>
                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="d-flex flex-column gap-3 h-100">

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

                        <div class="col-lg-8 mb-4">
                            <div class="card border-0 shadow-sm h-100 bg-light">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-lightning-charge-fill text-warning me-2"></i> Accesos Rápidos</h5>

                                    <div class="d-flex flex-column gap-3">

                                        <button class="btn btn-white text-start p-3 shadow-sm border-0 bg-white" style="transition: all 0.3s;">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle p-3 me-4" style="background-color: rgba(25, 135, 84, 0.1); color: #198754;">
                                                    <i class="bi bi-person-plus fs-3"></i>
                                                </div>
                                                <div>
                                                    <h5 class="fw-bold text-dark mb-1">Matricular Nuevo Alumno</h5>
                                                    <small class="text-muted">Inscribir a un estudiante en un grado y sección para este año escolar.</small>
                                                </div>
                                            </div>
                                        </button>

                                        <button class="btn btn-white text-start p-3 shadow-sm border-0 bg-white" style="transition: all 0.3s;">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle p-3 me-4" style="background-color: rgba(255, 193, 7, 0.1); color: #d39e00;">
                                                    <i class="bi bi-person-badge fs-3"></i>
                                                </div>
                                                <div>
                                                    <h5 class="fw-bold text-dark mb-1">Registrar Nuevo Docente</h5>
                                                    <small class="text-muted">Añadir personal al sistema y asignarles sus respectivas materias.</small>
                                                </div>
                                            </div>
                                        </button>

                                        <button class="btn btn-white text-start p-3 shadow-sm border-0 bg-white" style="transition: all 0.3s;">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle p-3 me-4 bg-light text-secondary">
                                                    <i class="bi bi-file-earmark-spreadsheet fs-3"></i>
                                                </div>
                                                <div>
                                                    <h5 class="fw-bold text-dark mb-1">Reporte de Calificaciones</h5>
                                                    <small class="text-muted">Exportar boletines o auditar notas ingresadas por los profesores.</small>
                                                </div>
                                            </div>
                                        </button>

                                        <button class="btn btn-white text-start p-3 shadow-sm border-0 bg-white" style="transition: all 0.3s;">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle p-3 me-4 bg-light text-secondary">
                                                    <i class="bi bi-database-down fs-3"></i>
                                                </div>
                                                <div>
                                                    <h5 class="fw-bold text-dark mb-1">Copia de Seguridad</h5>
                                                    <small class="text-muted">Generar y descargar un respaldo completo de la base de datos.</small>
                                                </div>
                                            </div>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Gestión de Usuarios -->
                <div class="tab-pane fade <?php echo ($active == 'usuarios') ? 'show active' : ''; ?>" id="vista-usuarios">

                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <div>
                            <h2 class="fw-bold text-dark mb-0">Gestión de Usuarios</h2>
                            <p class="text-muted mb-0">Administre los accesos, contraseñas y roles del sistema.</p>
                        </div>
                        <button class="btn text-white shadow-sm fw-semibold" style="background-color: var(--verde-institucional);" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                            <i class="bi bi-person-plus-fill me-2"></i> Nuevo Usuario
                        </button>
                    </div>
                    <div class="card border-0 shadow-sm mb-4 bg-white">
                        <div class="card-body p-3">
                            <form>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <select class="form-select border-0 bg-light">
                                            <option value="" selected>Todos los Roles</option>
                                            <option value="admin">Administradores</option>
                                            <option value="docente">Docentes</option>
                                            <option value="estudiante">Estudiantes</option>
                                        </select>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <span class="input-group-text border-0 bg-light text-muted"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control border-0 bg-light" placeholder="Buscar por nombre de usuario...">
                                            <button type="button" class="btn text-white px-4 shadow-sm" style="background-color: var(--verde-institucional);">Buscar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-muted" style="font-size: 0.85rem; text-transform: uppercase;">
                                        <tr>
                                            <th class="ps-4 py-3">Usuario / Acceso</th>
                                            <th class="py-3">Rol en el Sistema</th>
                                            <th class="py-3">Estado</th>
                                            <th class="text-end pe-4 py-3">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($usuarios)): ?>
                                            <?php foreach ($usuarios as $user): ?>
                                                <tr>
                                                    <td class="ps-4 fw-bold text-dark">
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-light rounded-circle p-2 me-3 text-secondary">
                                                                <i class="bi bi-person-fill fs-5"></i>
                                                            </div>
                                                            <?php echo htmlspecialchars($user['usuario'] ?? $user['username'] ?? 'Desconocido'); ?>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <?php
                                                        $rol = strtolower($user['rol'] ?? '');
                                                        $claseBadge = 'bg-secondary'; // Por defecto

                                                        if ($rol == 'admin') $claseBadge = 'badge-admin';
                                                        if ($rol == 'docente') $claseBadge = 'badge-profesor';
                                                        if ($rol == 'estudiante') $claseBadge = 'badge-estudiante';
                                                        ?>
                                                        <span class="badge <?php echo $claseBadge; ?> rounded-pill px-3 py-2 text-uppercase" style="font-size: 0.75rem;">
                                                            <?php echo htmlspecialchars($rol); ?>
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="text-success small fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Activo</span>
                                                    </td>

                                                    <td class="text-end pe-4">
                                                        <button class="btn btn-sm btn-light text-primary me-1 shadow-sm" title="Editar Contraseña"><i class="bi bi-key-fill"></i></button>
                                                        <button class="btn btn-sm btn-light text-danger shadow-sm" title="Bloquear"><i class="bi bi-lock-fill"></i></button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted">
                                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                                    No se encontraron usuarios registrados.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grados  -->
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
                            <form method="GET" action="sistema_admin.php">
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
                                            <th class="py-3">Maestro Guía</th>
                                            <th class="text-end pe-4 py-3">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($listaGrados)): ?>
                                            <?php foreach ($listaGrados as $fila): ?>
                                                <tr>
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
                                                        <span class="text-muted fst-italic small"><i class="bi bi-person-x me-1"></i> Sin asignar</span>
                                                    </td>

                                                    <td class="text-end pe-4">
                                                        <button class="btn btn-sm btn-light text-primary me-1 shadow-sm"><i class="bi bi-gear-fill"></i></button>
                                                        <button class="btn btn-sm btn-light text-success shadow-sm"><i class="bi bi-journals"></i></button>
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
                </div>
                <!-- Respaldo del Sistema -->

                <div class="tab-pane fade <?php echo ($active == 'respaldo') ? 'show active' : ''; ?>" id="vista-respaldo">

                    <!-- Título y Descripción -->
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <div>
                            <h2 class="fw-bold text-dark mb-0">Respaldo del Sistema</h2>
                            <p class="text-muted mb-0">Genere copias de seguridad de la base de datos o restaure información previa.</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Tarjeta de Exportación (Backup) -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="border-top: 5px solid var(--verde-institucional);">
                                <div class="card-body p-4 text-center d-flex flex-column">
                                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                                        <i class="bi bi-cloud-arrow-down-fill fs-1 text-success"></i>
                                    </div>
                                    <h4 class="fw-bold text-dark mb-3">Crear Copia de Seguridad</h4>
                                    <p class="text-muted small mb-4 flex-grow-1">Descarga un archivo <strong>.sql</strong> con toda la información actual de estudiantes, docentes, matrículas y calificaciones. Se recomienda realizar esta acción de forma semanal.</p>

                                    <button type="button" class="btn btn-amarillo-institucional px-4 py-3 w-100 shadow-sm rounded-3">
                                        <i class="bi bi-download me-2"></i> Descargar Respaldo Ahora
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta de Importación (Restaurar) -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="border-top: 5px solid #dc3545;">
                                <div class="card-body p-4 text-center d-flex flex-column">
                                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                                        <i class="bi bi-cloud-arrow-up-fill fs-1 text-danger"></i>
                                    </div>
                                    <h4 class="fw-bold text-dark mb-3">Restaurar Sistema</h4>
                                    <p class="text-muted small mb-4 flex-grow-1">Sube un archivo de respaldo previo para restaurar el sistema. <br><strong class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> Advertencia:</strong> Esto sobreescribirá todos los datos actuales.</p>

                                    <div class="input-group mb-3 text-start shadow-sm rounded-3 overflow-hidden">
                                        <input type="file" class="form-control bg-light border-0 py-2" id="archivoSql" accept=".sql">
                                    </div>

                                    <button type="button" class="btn btn-outline-danger px-4 py-2 w-100 shadow-sm rounded-3 fw-bold">
                                        <i class="bi bi-upload me-2"></i> Subir y Restaurar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historial Simulado -->
                    <div class="card border-0 shadow-sm mt-4 bg-white">
                        <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-muted"></i> Últimos Respaldos Generados</h5>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <ul class="list-group list-group-flush border-0">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-bottom py-3 bg-transparent">
                                    <div>
                                        <i class="bi bi-file-earmark-code-fill text-success me-3 fs-5"></i>
                                        <span class="fw-semibold text-dark">backup_cpsil_20260523.sql</span>
                                    </div>
                                    <span class="badge bg-light text-muted rounded-pill px-3 py-2 border">Hace 7 días</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 bg-transparent">
                                    <div>
                                        <i class="bi bi-file-earmark-code-fill text-success me-3 fs-5"></i>
                                        <span class="fw-semibold text-dark">backup_cpsil_20260516.sql</span>
                                    </div>
                                    <span class="badge bg-light text-muted rounded-pill px-3 py-2 border">Hace 14 días</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                <!-- Configuración del Sistema -->
                <div class="tab-pane fade <?php echo ($active == 'configuracion') ? 'show active' : ''; ?>" id="vista-configuracion">

                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <div>
                            <h2 class="fw-bold text-dark mb-0">Configuración del Sistema</h2>
                            <p class="text-muted mb-0">Controle los periodos de evaluación, bloqueos de notas y accesos de seguridad.</p>
                        </div>
                        <button type="submit" form="formConfiguracion" class="btn text-white px-4 shadow-sm fw-semibold" style="background-color: var(--verde-institucional);">
                            <i class="bi bi-save-fill me-2"></i> Guardar Cambios
                        </button>
                    </div>

                    <form id="formConfiguracion" action="sistema_admin.php" method="POST">
                        <input type="hidden" name="accion" value="guardar_configuracion">

                        <div class="row g-4">
                            <div class="col-lg-7">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-calendar-check me-2 text-primary"></i> Control Académico</h5>
                                    </div>
                                    <div class="card-body p-4">

                                        <div class="row g-3 mb-4 pb-4 border-bottom">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-semibold text-uppercase">Año Escolar Activo</label>
                                                <select class="form-select bg-light border-0 fw-bold">
                                                    <option value="2025">2025</option>
                                                    <option value="2026" selected>2026</option>
                                                    <option value="2027">2027</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-semibold text-uppercase">Corte Evaluativo Actual</label>
                                                <select class="form-select bg-light border-0">
                                                    <option value="1">I Corte</option>
                                                    <option value="2" selected>II Corte</option>
                                                    <option value="3">III Corte</option>
                                                    <option value="4">IV Corte</option>
                                                </select>
                                            </div>
                                        </div>

                                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-lock-fill me-2 text-danger"></i> Permisos de Ingreso de Notas (Docentes)</h6>
                                        <p class="text-muted small mb-3">Active o desactive los interruptores para permitir o bloquear que los profesores suban o modifiquen calificaciones en cada corte.</p>

                                        <div class="list-group list-group-flush border-0">
                                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-bottom-0">
                                                <span class="fw-semibold text-muted"><del>I Corte Evaluativo</del> (Finalizado)</span>
                                                <div class="form-check form-switch fs-5">
                                                    <input class="form-check-input" type="checkbox" role="switch" disabled>
                                                </div>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-bottom-0">
                                                <span class="fw-bold text-success">II Corte Evaluativo (Activo)</span>
                                                <div class="form-check form-switch fs-5">
                                                    <input class="form-check-input bg-success" type="checkbox" role="switch" checked>
                                                </div>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-bottom-0">
                                                <span class="fw-semibold text-dark">III Corte Evaluativo</span>
                                                <div class="form-check form-switch fs-5">
                                                    <input class="form-check-input" type="checkbox" role="switch">
                                                </div>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-bottom-0">
                                                <span class="fw-semibold text-dark">IV Corte Evaluativo</span>
                                                <div class="form-check form-switch fs-5">
                                                    <input class="form-check-input" type="checkbox" role="switch">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-shield-lock me-2 text-warning"></i> Seguridad y Accesos</h5>
                                    </div>
                                    <div class="card-body p-4">

                                        <div class="mb-4">
                                            <h6 class="fw-bold text-dark mb-2">Portal de Estudiantes / Padres</h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" role="switch" id="verNotas" checked>
                                                <label class="form-check-label text-muted small" for="verNotas">Permitir a estudiantes ver sus notas actuales.</label>
                                            </div>
                                        </div>

                                        <hr class="text-muted my-4">

                                        <div class="mb-4">
                                            <h6 class="fw-bold text-danger mb-2">Modo Mantenimiento</h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" role="switch" id="modoMantenimiento">
                                                <label class="form-check-label text-muted small" for="modoMantenimiento">Bloquear acceso al sistema a todos los usuarios (excepto Administradores).</label>
                                            </div>
                                        </div>

                                        <hr class="text-muted my-4">

                                        <div>
                                            <label class="form-label text-muted small fw-semibold text-uppercase">Cierre automático de sesión</label>
                                            <select class="form-select bg-light border-0">
                                                <option value="15">Tras 15 minutos de inactividad</option>
                                                <option value="30" selected>Tras 30 minutos de inactividad</option>
                                                <option value="60">Tras 1 hora de inactividad</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>

</body>

</html>