<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal San Ignacio - Panel de Administracion</title>
    <link rel="icon" type="image/png" href="../Imagenes/Logo_bg.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
