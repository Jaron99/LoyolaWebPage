<?php

require_once "../models/usuarios.model.php";
$usuariosModel = new Usuarios();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {

    //Accion para editar un usuario
    if ($_POST['accion'] === 'editar_password') {
        $usuarioeditar = $_POST['usuarioeditar'];
        $nuevacontraseña = $_POST['nuevacontraseña'];
        $usuariosModel -> cambiarcontrasena($usuarioeditar, $nuevacontraseña);
        header("Location:../views/admin.view.php?tab=usuarios");
        exit();
    }

    //Accion Bloqueo o Desbloqueo de Usuario
    if ($_POST['accion'] === 'bloquear_usuario') {
        $usuarioBloquear = $_POST['usuarioBloquear'];
        $nuevoEstado = $_POST['nuevoEstado'];
        $usuariosModel -> bloquearUsuario($usuarioBloquear, $nuevoEstado);
        header("Location:../views/admin.view.php?tab=usuarios");
        exit();
    }

    //Accion para Eliminar un Usuario
    if ($_POST['accion'] === 'eliminar_usuario') {
        $usuarioEliminar = $_POST['usuarioEliminar'];
        $usuariosModel -> eliminarUsuario($usuarioEliminar);
        header("Location:../views/admin.view.php?tab=usuarios");
        exit();
    }

    //Accion para Crear un Usuario
    if ($_POST['accion'] === 'crear_usuario') {
        $usuarioNuevo = $_POST['usuarioNuevo'];
        $contrasenaNueva = $_POST['contrasenaNueva'];
        $rolNuevo = $_POST['rolNuevo'];
        $usuariosModel -> crearUsuario($usuarioNuevo, $contrasenaNueva, $rolNuevo);
        header("Location:../views/admin.view.php?tab=usuarios");
        exit();
    }
}


?>
