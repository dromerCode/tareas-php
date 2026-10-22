<?php
require_once __DIR__ . '/../repositories/TareaRepository.php';
require_once __DIR__ . '/../repositories/UsuarioRepository.php';
require_once __DIR__ . '/../repositories/CategoriaRepository.php';

// $usuario (el objeto Usuario logueado) viene de userController
$tareaRepository = new TareaRepository($db);
$usuarioRepository = new UsuarioRepository($db);
$categoriaRepository = new CategoriaRepository($db);

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
        // "Sin categoría" llega como "" y se guarda como NULL
        if ($_POST['crear_categoria'] == "") {
            $idCategoria = null;
        } else {
            $idCategoria = (int) $_POST['crear_categoria'];
        }
        // El id es null porque lo pone la base de datos, y los nombres no hacen falta para guardar.
        $tarea = new Tarea(null, trim($_POST['crear_titulo']), trim($_POST['crear_descripcion']), 'pendiente', (int) $_POST['crear_usuario'], null, $idCategoria, null);
        $tareaRepository->save($tarea);
        header("Location: index.php");
        exit;
    }
}

if ($usuario->esAdmin() && isset($_POST['editar_id'])) {
    if (trim($_POST['editar_titulo']) == "") {
        $mensaje = "El título no puede estar vacío";
    } else {
        if ($_POST['editar_categoria'] == "") {
            $idCategoria = null;
        } else {
            $idCategoria = (int) $_POST['editar_categoria'];
        }
        $tarea = new Tarea((int) $_POST['editar_id'], trim($_POST['editar_titulo']), trim($_POST['editar_descripcion']), $_POST['editar_estado'], (int) $_POST['editar_usuario'], null, $idCategoria, null);
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
        $categorias = $categoriaRepository->findAll();
        require __DIR__ . '/../views/editarView.phtml';
        exit;
    }
}

// Página principal: el admin ve todas las tareas, el usuario solo las suyas.
// ?orden=... -> columna por la que se ordena la tabla (el repositorio comprueba que sea válida)
$orden = null;
if (isset($_GET['orden'])) {
    $orden = $_GET['orden'];
}
if ($usuario->esAdmin()) {
    $categorias = $categoriaRepository->findAll();
    $tareas = $tareaRepository->findAll($orden);
    $usuarios = $usuarioRepository->findAll();
} else {
    $tareas = $tareaRepository->findByUsuario($usuario->getId(), $orden);
}
require __DIR__ . '/../views/mainView.phtml';
