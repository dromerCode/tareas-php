<?php
require_once __DIR__ . '/../repositories/TareaRepository.php';
require_once __DIR__ . '/../repositories/UsuarioRepository.php';

// $usuario (el objeto Usuario logueado) viene de userController
$tareaRepository = new TareaRepository($db);
$usuarioRepository = new UsuarioRepository($db);

// ---------- ACEPTAR Y COMPLETAR (usuario) ----------
// Solo se puede cambiar el estado de una tarea que tienes asignada tú.
if (isset($_POST['aceptar'])) {
    $tarea = $tareaRepository->findById((int) $_POST['aceptar']);
    if ($tarea && $tarea->getIdUsuario() == $usuario->getId()) {
        $tareaRepository->cambiarEstado($tarea->getId(), 'aceptada');
    }
    header("Location: index.php");
    exit;
}

if (isset($_POST['completar'])) {
    $tarea = $tareaRepository->findById((int) $_POST['completar']);
    if ($tarea && $tarea->getIdUsuario() == $usuario->getId()) {
        $tareaRepository->cambiarEstado($tarea->getId(), 'completada');
    }
    header("Location: index.php");
    exit;
}

// ---------- CREAR, EDITAR Y BORRAR (solo admin) ----------
if ($usuario->esAdmin() && isset($_POST['crear_titulo'])) {
    if (trim($_POST['crear_titulo']) == "") {
        $mensaje = "El título no puede estar vacío";
    } else {
        // El id es null porque lo pone la base de datos, y el nombre no hace falta para guardar.
        $tarea = new Tarea(null, trim($_POST['crear_titulo']), trim($_POST['crear_descripcion']), 'pendiente', (int) $_POST['crear_usuario'], null);
        $tareaRepository->save($tarea);
        header("Location: index.php");
        exit;
    }
}

if ($usuario->esAdmin() && isset($_POST['editar_id'])) {
    if (trim($_POST['editar_titulo']) == "") {
        $mensaje = "El título no puede estar vacío";
    } else {
        $tarea = new Tarea((int) $_POST['editar_id'], trim($_POST['editar_titulo']), trim($_POST['editar_descripcion']), $_POST['editar_estado'], (int) $_POST['editar_usuario'], null);
        $tareaRepository->update($tarea);
        header("Location: index.php");
        exit;
    }
}

if ($usuario->esAdmin() && isset($_POST['borrar'])) {
    $tareaRepository->delete((int) $_POST['borrar']);
    header("Location: index.php");
    exit;
}

// ---------- VISTAS DE TAREAS ----------
// ?var=4&id=... -> el admin edita esa tarea
if ($usuario->esAdmin() && isset($_GET['var']) && $_GET['var'] == 4) {
    $tarea = $tareaRepository->findById((int) $_GET['id']);
    if ($tarea) {
        $usuarios = $usuarioRepository->findAll();
        require __DIR__ . '/../views/editarView.phtml';
        exit;
    }
}

// Página principal: el admin ve todas las tareas, el usuario solo las suyas.
if ($usuario->esAdmin()) {
    $tareas = $tareaRepository->findAll();
    $usuarios = $usuarioRepository->findAll();
} else {
    $tareas = $tareaRepository->findByUsuario($usuario->getId());
}
require __DIR__ . '/../views/mainView.phtml';
