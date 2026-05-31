<?php
session_start();

include_once "Models/usuarios.model.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contrasena_ingresada = $_POST["contrasena"];

    $objUsuario= new Usuarios();
    
    // 1. Buscamos al usuario en la BD solo por su nombre
    $user = $objUsuario->login($correo);

    if ($user && $user->num_rows > 0) {
        $fila = $user->fetch_assoc();

        $hash_guardado = $fila["contrasena"]; 

        if (password_verify($contrasena_ingresada, $hash_guardado)) {
            
            $_SESSION["user_usuario"] = $fila["id_usuario"];
            $_SESSION["usuario"] = $fila["usuario"];
            $_SESSION["rol"] = $fila["rol"];

            if ($fila["rol"] == "admin") {
                header("Location: sistema_admin.php");
                exit();
            } elseif ($fila["rol"] == "estudiante") {
                header("Location: sistema_estudiante.php");
                exit();
            } elseif ($fila["rol"] == "docente") {
                header("Location: sistema_docente.php");
                exit();
            } else {
                $error = "Rol de usuario no reconocido en el sistema.";
            }
        } else {
            // El usuario existe, pero la contraseña no coincide
            $error = "Contraseña incorrecta.";
        }
    } else {
        // No se encontró el nombre de usuario
        $error = "El usuario no existe o está inactivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Acceso - Colegio Parroquial San Ignacio de Loyola</title>
    <link rel="icon" type="image/png" href="Imagenes/Logo_bg.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* --- ESTILOS PERSONALIZADOS --- */

        /* 1. Fondo con Imagen y Desenfoque (Blur) */
        body {
            background-image: url('Imagenes/bg_login.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;

            /* AJUSTE RESPONSIVE: Permite que el contenedor crezca y se adapte */
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            /* El padding asegura que la tarjeta nunca toque los bordes de la pantalla */
            padding: 20px; 
        }

        /* Capa de desenfoque sobre el fondo */
        body::before {
            content: "";
            /* AJUSTE RESPONSIVE: 'fixed' asegura que el blur no se rompa al hacer scroll por zoom */
            position: fixed; 
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            z-index: 0;
        }

        /* 2. Contenedor Principal y Tarjeta */
        .login-container {
            z-index: 1;
            /* AJUSTE RESPONSIVE: 'margin: auto' es el secreto para centrar de forma segura. 
               Si no cabe, simplemente se empuja hacia arriba permitiendo hacer scroll normal. */
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

        /* 3. Estilos Institucionales (Colores y Botones) */
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

                <div class="text-center mb-4">
                    <div class="mb-3">
                        <a href="/" class="Logo">
                            <img src="Imagenes/Logo.png" alt="Logo" class="img-fluid" style="max-height: 90px;">
                        </a>
                    </div>
                    <h2 class="h5 fw-bold text-institucional-verde mb-1">Colegio Parroquial</h2>
                    <h1 class="h6 text-institucional-verde mb-2">San Ignacio de Loyola</h1>
                    <p class="text-muted small mb-0">Portal de Acceso Unificado</p>
                </div>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger text-center small fw-bold py-2 mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label text-muted small mb-1">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-person"></i></span>
                            
                            <!-- EL ATRIBUTO name="correo" ES LA CLAVE -->
                            <input type="text" class="form-control" id="username" name="correo" placeholder="admin"
                                aria-label="Usuario" aria-describedby="basic-addon1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label text-muted small mb-1">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon2"><i class="bi bi-lock"></i></span>
                            
                            <!-- EL ATRIBUTO name="contrasena" ES LA CLAVE -->
                            <input type="password" class="form-control" id="password" name="contrasena" placeholder="••••••••"
                                aria-label="Contraseña" aria-describedby="basic-addon2" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <!-- El botón debe ser type="submit" -->
                        <button type="submit" class="btn btn-institucional-amarillo btn-lg" style="font-size: 1rem;">ENTRAR A MI CUENTA</button>
                    </div>  
                </form>
                <div class="text-center mt-3">
                    <a href="#" class="text-institucional-verde small text-decoration-none">¿Olvidaste tu contraseña?</a>
                </div>

                <hr class="my-4 text-muted">

                <div class="text-center mt-3 text-muted" style="font-size: 0.75rem;">
                    © 2026 Colegio Parroquial San Ignacio de Loyola.<br>
                    Managua, Nicaragua.
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>