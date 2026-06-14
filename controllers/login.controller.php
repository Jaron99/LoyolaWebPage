<?php
session_start();
require_once '../models/usuarios.model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    $objUsuario = new Usuarios();
    $user = $objUsuario->login($correo);

    if ($user && $user->num_rows > 0) {
        $fila = $user->fetch_assoc();
        $hash_guardado = $fila['contrasena'];
        if (password_verify($contrasena, $hash_guardado)|| $contrasena === $hash_guardado) {
            
            $_SESSION['id_usuario'] = $fila['id_usuario'];
            $_SESSION['usuario'] = $fila['usuario'];
            $_SESSION['rol'] = $fila['rol'];

            if ($fila['rol'] === 'admin') {
                header("Location: /views/admin.view.php");
            } elseif ($fila['rol'] === 'docente') {
                header("Location: /views/docente.view.php");
            } elseif ($fila['rol'] === 'estudiante') {
                header("Location: /views/estudiante.view.php");
            }else {
                $_SESSION['error'] = "Rol de usuario no reconocido en el sistema.";
                header("Location: /views/login.view.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
            header("Location: /views/login.view.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "El usuario no existe.";
        header("Location: /views/login.view.php");
        exit();
    }
}
?>