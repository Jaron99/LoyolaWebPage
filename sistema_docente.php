<?php
session_start();
// Si no hay una sesión activa o el rol no es docente, lo expulsamos al login
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "docente") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal San Ignacio - Panel de Profesor</title>
    <link rel="icon" type="image/png" href="Imagenes/Logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --verde-institucional: #006a28;
            --amarillo-institucional: #ffd709;
        }

        /* Fondo general gris muy claro para no cansar la vista */
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
            border-radius: 0 25px 25px 0; /* Borde redondeado solo a la derecha */
            margin-right: 15px;
        }

        .nav-pills .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Botón Activo en el Menú */
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

        /* Barra Superior (Topbar) */
        .topbar {
            background-color: white;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .text-verde-institucional { color: var(--verde-institucional) !important; }

        /* Botones y Formularios */
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

        /* Diseño de la Tabla de Notas */
        .tabla-notas input[type="number"] {
            width: 75px;
            text-align: center;
            padding: 0.25rem;
            font-weight: 500;
        }
        
        /* Ocultar flechitas de los inputs numéricos */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }

        /* Responsividad (Móviles) */
        @media (max-width: 768px) {
            .sidebar { margin-left: -260px; } /* Oculta la barra lateral */
            .sidebar.show { margin-left: 0; }  /* Muestra la barra al tocar el botón */
            .main-content { margin-left: 0; width: 100%; }
        }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column py-3" id="sidebar">
        <div class="text-center mb-4 mt-2">
            <img src="Imagenes/Logo.png" alt="Logo" class="sidebar-logo mb-2">
            <h5 class="fw-bold mb-0 text-white">Portal San Ignacio</h5>
            <small class="text-white-50">Sistema Académico</small>
        </div>
        
        <ul class="nav nav-pills flex-column mb-auto mt-2">
            <li class="nav-item">
                <a href="#" class="nav-link text-white">
                    <i class="bi bi-grid-1x2-fill me-3"></i> Inicio
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link active" aria-current="page">
                    <i class="bi bi-journal-check me-3"></i> Registro de Notas
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-white">
                    <i class="bi bi-bar-chart-fill me-3"></i> Reportes
                </a>
            </li>
        </ul>
        
        <div class="text-center text-white-50 small mt-4">
            <i class="bi bi-calendar3 me-1"></i> Ciclo Escolar 2026
        </div>
    </div>

    <div class="main-content" id="main-content">
        
        <nav class="navbar navbar-expand-lg topbar px-4 py-3 sticky-top">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary d-md-none me-3" type="button" id="sidebarToggle">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <h5 class="mb-0 text-verde-institucional fw-bold d-none d-md-block">Panel de Administración</h5>
                </div>
                
                <div class="d-flex align-items-center">
                    <div class="text-end me-3 d-none d-sm-block">
                        <div class="fw-bold text-dark" style="line-height: 1.2;">Prof. María González</div>
                        <small class="text-muted">Departamento de Ciencias</small>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-2 text-verde-institucional"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i> Mi Perfil</a></li>
                            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-gear me-2"></i> Configuración</a></li>
                            <li><a class="dropdown-item py-2" href="/sistema_admin.html"><i class="bi bi-gear me-2"></i> Cambiar a Admin</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="mb-4">
                <h2 class="fw-bold text-dark">Registro de Calificaciones</h2>
                <p class="text-muted">Seleccione el grado y asignatura para ingresar las notas del periodo actual.</p>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-top: 4px solid var(--verde-institucional);">
                <div class="card-body p-4">
                    <form class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="gradoSelect" class="form-label text-muted fw-bold mb-1">Grado / Sección</label>
                            <select id="gradoSelect" class="form-select bg-light">
                                <option value="">Seleccione un grado...</option>
                                <option value="4A" selected>4to Año - Sección A</option>
                                <option value="4B">4to Año - Sección B</option>
                                <option value="5A">5to Año - Sección A</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="asignaturaSelect" class="form-label text-muted fw-bold mb-1">Asignatura</label>
                            <select id="asignaturaSelect" class="form-select bg-light">
                                <option value="">Seleccione una asignatura...</option>
                                <option value="mat" selected>Matemáticas Avanzadas</option>
                                <option value="fis">Física Fundamental</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-secondary w-100 fw-bold">
                                <i class="bi bi-search me-2"></i> Cargar Lista
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="background-color: #e6ffe6; color: #006a28;">
                <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                <div>
                    <strong>III Corte Activo:</strong> El ingreso de notas estará habilitado hasta el Viernes 15 de Noviembre. Recuerde guardar los cambios.
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden mb-5">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Alumnos de 4to Año - A</h5>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">34 Estudiantes</span>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 tabla-notas">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center text-muted" style="width: 50px;">#</th>
                                    <th scope="col" class="text-muted">Estudiante</th>
                                    <th scope="col" class="text-center text-muted">I Corte</th>
                                    <th scope="col" class="text-center text-muted">II Corte</th>
                                    <th scope="col" class="text-center bg-warning bg-opacity-10 text-dark">III Corte</th>
                                    <th scope="col" class="text-center text-muted">IV Corte</th>
                                    <th scope="col" class="text-center fw-bold text-dark">Promedio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center text-muted">1</td>
                                    <td>
                                        <div class="fw-bold text-dark">Alvarez, Gabriel</div>
                                        <small class="text-muted">ID: 2026001</small>
                                    </td>
                                    <td class="text-center"><input type="number" class="form-control mx-auto" value="85" disabled></td>
                                    <td class="text-center"><input type="number" class="form-control mx-auto" value="90" disabled></td>
                                    <td class="text-center bg-warning bg-opacity-10 p-2">
                                        <input type="number" class="form-control mx-auto shadow-sm" value="88" placeholder="0-100" min="0" max="100">
                                    </td>
                                    <td class="text-center"><input type="number" class="form-control mx-auto bg-light border-0" disabled placeholder="-"></td>
                                    <td class="text-center fw-bold text-verde-institucional fs-5">87.6</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-muted">2</td>
                                    <td>
                                        <div class="fw-bold text-dark">Bermúdez, Camila</div>
                                        <small class="text-muted">ID: 2026002</small>
                                    </td>
                                    <td class="text-center"><input type="number" class="form-control mx-auto" value="70" disabled></td>
                                    <td class="text-center"><input type="number" class="form-control mx-auto" value="75" disabled></td>
                                    <td class="text-center bg-warning bg-opacity-10 p-2">
                                        <input type="number" class="form-control mx-auto shadow-sm" value="" placeholder="0-100">
                                    </td>
                                    <td class="text-center"><input type="number" class="form-control mx-auto bg-light border-0" disabled placeholder="-"></td>
                                    <td class="text-center fw-bold text-muted fs-5">72.5</td>
                                </tr>
                                <tr>
                                    <td class="text-center text-muted">3</td>
                                    <td>
                                        <div class="fw-bold text-danger">Castillo, Diego</div>
                                        <small class="text-muted">ID: 2026003</small>
                                    </td>
                                    <td class="text-center"><input type="number" class="form-control mx-auto text-danger fw-bold" value="45" disabled></td>
                                    <td class="text-center"><input type="number" class="form-control mx-auto text-danger fw-bold" value="50" disabled></td>
                                    <td class="text-center bg-warning bg-opacity-10 p-2">
                                        <input type="number" class="form-control mx-auto shadow-sm" value="" placeholder="0-100">
                                    </td>
                                    <td class="text-center"><input type="number" class="form-control mx-auto bg-light border-0" disabled placeholder="-"></td>
                                    <td class="text-center fw-bold text-danger fs-5">47.5</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white p-4 d-flex justify-content-end border-top">
                    <button type="button" class="btn btn-amarillo-institucional btn-lg px-5 shadow-sm">
                        <i class="bi bi-save me-2"></i> Guardar Calificaciones
                    </button>
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