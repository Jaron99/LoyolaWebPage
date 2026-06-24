<?php
session_start();

// PREVENCIÓN DE CACHÉ: Obliga al navegador a recargar la página al darle "Atrás"
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['error']);

$sesion_activa = isset($_SESSION['rol']) && isset($_SESSION['usuario']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Acceso - Colegio Parroquial San Ignacio de Loyola</title>
    <link rel="icon" type="image/png" href="../Imagenes/Logo_bg.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('../Imagenes/bg_login.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            z-index: 0;
        }

        .login-container {
            z-index: 1;
            margin: auto;
            width: 100%;
            max-width: 400px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.95);
        }

        .text-institucional-verde {
            color: #198754 !important;
        }

        .btn-institucional-amarillo {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-institucional-amarillo:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            color: #000;
            transform: translateY(-2px);
        }

        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #198754;
        }

        .form-control {
            border-left: none;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="card p-4">
            <div class="card-body p-2">

                <div class="text-center mb-3">
                    <a href="../index.php" class="Logo">
                        <img src="../Imagenes/Logo.png" alt="Logo" class="img-fluid mb-3" style="max-height: 90px;">
                    </a>
                    <h2 class="h5 fw-bold text-institucional-verde mb-1">Colegio Parroquial</h2>
                    <h1 class="h6 text-institucional-verde mb-3">San Ignacio de Loyola</h1>
                </div>

                <?php if ($sesion_activa): ?>

                    <?php
                    // Determinamos a qué panel debe ir el usuario según su rol
                    $ruta_panel = 'estudiante.view.php?tab=panelestudiante';
                    if ($_SESSION['rol'] == 'admin') $ruta_panel = 'admin.view.php?tab=panel';
                    if ($_SESSION['rol'] == 'docente') $ruta_panel = 'docente.view.php?tab=paneldocente'; // ¡AQUÍ!
                    ?>

                    <div class="modal-content border shadow-sm rounded-3 mt-3 text-start">
                        <div class="modal-header border-bottom px-4 py-3 bg-light rounded-top-3">
                            <h4 class="modal-title fw-bold text-dark mb-0" style="font-size: 1.1rem;">Confirmar</h4>
                        </div>

                        <div class="modal-body px-4 py-4">
                            <p class="text-muted mb-0" style="font-size: 0.95rem;">
                                Tienes una sesion activa como <strong><?php echo strtoupper(htmlspecialchars($_SESSION['usuario'])); ?></strong>, cierra la sesion antes de ingresar a otra cuenta.
                            </p>
                        </div>

                        <div class="modal-footer border-top px-4 py-3 bg-light rounded-bottom-3 d-flex justify-content-end gap-2 m-0">
                            <a href="<?php echo $ruta_panel; ?>" class="btn btn-secondary shadow-sm">
                                Cancelar
                            </a>
                            <a href="../controllers/logout.controller.php" class="btn btn-institucional-amarillo shadow-sm">
                                Cerrar sesión
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-muted small mb-3 text-center">Portal de Acceso</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger text-center small fw-bold py-2 mb-3" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="../controllers/login.controller.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label text-muted small mb-1">Usuario</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="username" name="usuario" placeholder="Ej. admin" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label text-muted small mb-1">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="contrasena" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-institucional-amarillo btn-lg" style="font-size: 1rem;">ENTRAR A MI CUENTA</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="#" class="text-institucional-verde small text-decoration-none">¿Olvidaste tu contraseña?</a>
                    </div>
                <?php endif; ?>

                <hr class="my-4 text-muted">
                <div class="text-center mt-2 text-muted" style="font-size: 0.75rem;">
                    © 2026 Colegio Parroquial San Ignacio de Loyola.<br>
                    Managua, Nicaragua.
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Forzar recarga si la página se muestra desde la caché del historial (botón "Atrás")
        window.addEventListener('pageshow', function(event) {
            // event.persisted indica si la página se cargó desde la memoria caché del navegador
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>

</html>