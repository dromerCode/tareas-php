<?php
require_once __DIR__ . '/../repositories/UsuarioRepository.php';

// La gestión de usuarios es solo para el admin
if (($_SESSION['rol'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit;
}
$usuarioRepo = new UsuarioRepository($db);

if ($page == 'cambiar_rol') {
    $id = (int)$_POST['id_usuario'];
    $rol = $_POST['rol'];
    // No te puedes quitar el admin a ti mismo (te quedarías fuera)
    if ($id != $_SESSION['id_usuario']) {
        if ($rol == 'admin' || $rol == 'usuario') {
            $usuarioRepo->cambiarRol($id, $rol);
        }
    }
    header("Location: index.php?page=usuarios");
    exit;
}

if ($page == 'borrar_usuario') {
    $id = (int)$_POST['id_usuario'];
    if ($id != $_SESSION['id_usuario']) {
        $usuarioRepo->delete($id);
    }
    header("Location: index.php?page=usuarios");
    exit;
}

$usuarios = $usuarioRepo->findAll();
require __DIR__ . '/../views/usuariosView.phtml';
