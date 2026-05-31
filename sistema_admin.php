<?php
session_start();
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: login.php");
    exit();
}

include_once "Models/admin.model.php";

$adminModel = new Admin();
$usuarios = $adminModel->getUsuarios();
$resumenDashboard = $adminModel->obtenerResumenDashboard();
$nivelesAcademicos = $adminModel->obtenerNivelesAcademicos();

$filtronivel = isset($_GET['nivel']) ? $_GET['nivel'] : "";
$listaGrados = $adminModel->obtenerGradosSeccion($filtronivel);
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
        <ul class="nav nav-pills flex-column mb-auto mt-2" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="pill" href="#vista-panel" style="cursor: pointer;">
                    <i class="bi bi-speedometer2 me-3"></i> Panel Principal
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#vista-usuarios" style="cursor: pointer;">
                    <i class="bi bi-people-fill me-3"></i> Gestión de Usuarios
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#vista-grados" style="cursor: pointer;">
                    <i class="bi bi-diagram-3-fill me-3"></i> Grados y Secciones
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#vista-respaldo" style="cursor: pointer;">
                    <i class="bi bi-cloud-upload-fill me-3"></i> Respaldo del Sistema
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="pill" href="#vista-configuracion" style="cursor: pointer;">
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
                <div class="tab-pane fade show active" id="vista-panel" role="tabpanel">
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
                <div class="tab-pane fade" id="vista-usuarios" role="tabpanel">
                    <h2 class="fw-bold text-dark mb-4">Gestión de Usuarios</h2>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                        </div>

                        <div class="col-md-4 mb-3">
                        </div>
                    </div>
                </div>

                <!-- Grados  -->
                <div class="tab-pane fade" id="vista-grados" role="tabpanel">
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

                                <div class="row g-2">
                                    <div class="col-md-8">
                                        <select name="nivel" class="form-select border-0 bg-light">
                                            <option value="">Todos los Niveles</option>
                                            <?php
                                            if (!empty($listaNiveles)):
                                                foreach ($listaNiveles as $nivel):
                                                    // Esta validación revisa si este nivel es el que el usuario acaba de filtrar para dejarlo "seleccionado"
                                                    $seleccionado = ($filtronivel == $nivel) ? 'selected' : '';
                                            ?>
                                                    <option value="<?php echo $nivel; ?>" <?php echo $seleccionado; ?>>
                                                        <?php echo ucfirst($nivel ?? 'Sin Nivel'); ?>
                                                    </option>
                                            <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <button type="submit" class="btn text-white w-100 shadow-sm" style="background-color: #198754;">
                                            <i class="bi bi-funnel-fill"></i> Filtrar Datos
                                        </button>
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

                                                        echo $grado . ' "' . $seccion . '"';
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
                <div class="tab-pane fade" id="vista-respaldo" role="tabpanel">
                    <h2 class="fw-bold text-dark mb-4">Respaldo del Sistema</h2>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                        </div>

                        <div class="col-md-4 mb-3">
                        </div>
                    </div>
                </div>

                <!-- Configuración del Sistema -->
                <div class="tab-pane fade" id="vista-configuracion" role="tabpanel">
                    <h2 class="fw-bold text-dark mb-4">Configuración del Sistema</h2>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                        </div>

                        <div class="col-md-4 mb-3">
                        </div>
                    </div>
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