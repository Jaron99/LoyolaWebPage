<?php
session_start();
// Si no hay una sesión activa o el rol no es admin, lo expulsamos al login
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: login.php");
    exit();
}

    include_once "Models/admin.model.php";

    $adminModel = new Admin();
    $usuarios = $adminModel->getUsuarios();
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
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* --- ÁREA PRINCIPAL --- */
        .main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            transition: all 0.3s ease-in-out;
        }

        .topbar {
            background-color: white;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .text-verde-institucional { color: var(--verde-institucional) !important; }

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

        .form-control:focus, .form-select:focus {
            border-color: var(--amarillo-institucional);
            box-shadow: 0 0 0 0.25rem rgba(255, 215, 9, 0.25);
        }

        /* Insignias (Badges) de estado */
        .badge-estudiante { background-color: #0d6efd; color: white; }
        .badge-profesor { background-color: var(--verde-institucional); color: white; }
        .badge-admin { background-color: #212529; color: white; }

        @media (max-width: 768px) {
            .sidebar { margin-left: -260px; }
            .sidebar.show { margin-left: 0; }
            .main-content { margin-left: 0; width: 100%; }
        }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column py-3" id="sidebar">
        <div class="text-center mb-4 mt-2">
            <img src="Imagenes/Logo.png" alt="Logo" class="sidebar-logo mb-2">
            <h5 class="fw-bold mb-0 text-white">Portal San Ignacio</h5>
            <small class="text-white-50">Administración General</small>
        </div>
        
        <ul class="nav nav-pills flex-column mb-auto mt-2">
            <li class="nav-item">
                <a href="#" class="nav-link text-white">
                    <i class="bi bi-speedometer2 me-3"></i> Panel Principal
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link active" aria-current="page">
                    <i class="bi bi-people-fill me-3"></i> Gestión de Usuarios
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-white">
                    <i class="bi bi-diagram-3-fill me-3"></i> Grados y Secciones
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-white">
                    <i class="bi bi-file-earmark-bar-graph-fill me-3"></i> Reportes Globales
                </a>
            </li>
            <li class="nav-item mt-4">
                <a href="#" class="nav-link text-white-50">
                    <i class="bi bi-gear-fill me-3"></i> Configuración
                </a>
            </li>
        </ul>
        
        <div class="text-center text-white-50 small mt-4">
            <i class="bi bi-shield-lock me-1"></i> Acceso Nivel 1
        </div>
    </div>

    <div class="main-content" id="main-content">
        
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
                        <div class="fw-bold text-dark" style="line-height: 1.2;">Lic. Roberto Silva</div>
                        <small class="text-muted">Administrador del Sistema</small>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-2 text-verde-institucional"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i> Mi Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold text-dark mb-0">Gestión de Usuarios</h2>
                    <p class="text-muted mb-0">Administre las cuentas del personal y estudiantes del colegio.</p>
                </div>
                <button class="btn btn-amarillo-institucional btn-lg shadow-sm">
                    <i class="bi bi-person-plus-fill me-2"></i> Nuevo Usuario
                </button>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <form class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" class="form-control bg-light border-start-0" placeholder="Buscar por Nombre, Apellido o ID...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select bg-light">
                                <option value="" selected>Todos los Roles</option>
                                <option value="estudiante">Estudiantes</option>
                                <option value="profesor">Profesores</option>
                                <option value="admin">Administrativos</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select bg-light">
                                <option value="" selected>Estado: Todos</option>
                                <option value="activo">Activos</option>
                                <option value="inactivo">Inactivos</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-grid">
                            <button type="button" class="btn btn-outline-secondary" title="Filtrar">
                                <i class="bi bi-funnel-fill"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden mb-5" style="border-top: 4px solid var(--verde-institucional);">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Directorio Activo</h5>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">Total: 542 Usuarios</span>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4 text-muted">ID / Carnet</th>
                                    <th scope="col" class="text-muted">Nombre Completo</th>
                                    <th scope="col" class="text-muted">Rol Institucional</th>
                                    <th scope="col" class="text-muted">Correo Electrónico</th>
                                    <th scope="col" class="text-center text-muted">Estado</th>
                                    <th scope="col" class="text-center text-muted pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">ADM-001</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-person-fill text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">Silva, Roberto</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-admin px-2 py-1">Administrador</span></td>
                                    <td class="text-muted">rsilva@parroquial.edu.ni</td>
                                    <td class="text-center">
                                        <span class="text-success fw-bold"><i class="bi bi-circle-fill small me-1"></i> Activo</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Editar"><i class="bi bi-pencil-square"></i></button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Ver Detalles"><i class="bi bi-eye"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">DOC-145</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-person-fill text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">González, María</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-profesor px-2 py-1">Profesor</span></td>
                                    <td class="text-muted">mgonzalez@parroquial.edu.ni</td>
                                    <td class="text-center">
                                        <span class="text-success fw-bold"><i class="bi bi-circle-fill small me-1"></i> Activo</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Editar"><i class="bi bi-pencil-square"></i></button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Ver Detalles"><i class="bi bi-eye"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="ps-4 fw-bold text-dark">2026001</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-person-fill text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">Álvarez, Gabriel</div>
                                                <small class="text-muted">4to Año - A</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-estudiante px-2 py-1">Estudiante</span></td>
                                    <td class="text-muted">galvarez26@est.parroquial.edu.ni</td>
                                    <td class="text-center">
                                        <span class="text-success fw-bold"><i class="bi bi-circle-fill small me-1"></i> Activo</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Editar"><i class="bi bi-pencil-square"></i></button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Desactivar"><i class="bi bi-ban"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">2025112</td>
                                    <td>
                                        <div class="d-flex align-items-center opacity-50">
                                            <div class="bg-light rounded-circle p-2 me-3 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-person-fill text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-muted">López, Ricardo</div>
                                                <small class="text-muted">Retirado</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-secondary px-2 py-1">Estudiante</span></td>
                                    <td class="text-muted">rlopez25@est.parroquial.edu.ni</td>
                                    <td class="text-center">
                                        <span class="text-danger fw-bold"><i class="bi bi-circle-fill small me-1"></i> Inactivo</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Editar"><i class="bi bi-pencil-square"></i></button>
                                            <button type="button" class="btn btn-sm btn-outline-success" title="Activar"><i class="bi bi-check-circle"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white p-3 d-flex justify-content-between align-items-center border-top">
                    <span class="text-muted small">Mostrando 1 a 4 de 542 registros</span>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                            <li class="page-item active"><a class="page-link bg-success border-success" href="#">1</a></li>
                            <li class="page-item"><a class="page-link text-success" href="#">2</a></li>
                            <li class="page-item"><a class="page-link text-success" href="#">3</a></li>
                            <li class="page-item"><a class="page-link text-success" href="#">Siguiente</a></li>
                        </ul>
                    </nav>
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