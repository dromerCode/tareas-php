<?php
require_once __DIR__ . '/../repositories/TareaRepository.php';
require_once __DIR__ . '/../repositories/UsuarioRepository.php';
require_once __DIR__ . '/../repositories/CategoriaRepository.php';

// $usuario viene de userController
$tareaRepository = new TareaRepository($db);
$usuarioRepository = new UsuarioRepository($db);
$categoriaRepository = new CategoriaRepository($db);

// devuelve el id de la categoria elegida, o null si no tiene
// si se elige "nueva" la crea (si no existe ya)
function elegirCategoria($nueva, $seleccionada, $categoriaRepository){
    $nueva = trim($nueva);
    if ($seleccionada == "nueva" && $nueva != "") {
        if (!$categoriaRepository->findByNombre($nueva)) {
            $categoriaRepository->save(new Categoria(null, $nueva));
        }
        return $categoriaRepository->findByNombre($nueva)->getId();
    }
    if ($seleccionada == "" || $seleccionada == "nueva") {
        return null;
    }
    return (int) $seleccionada;
}

// aceptar y completar (solo tus tareas)
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

// crear, editar y borrar (solo admin)
if ($usuario->esAdmin() && isset($_POST['crear_titulo'])) {
    if (trim($_POST['crear_titulo']) == "") {
        $mensaje = "El título no puede estar vacío";
    } else {
        $idCategoria = elegirCategoria($_POST['nueva_categoria'], $_POST['crear_categoria'], $categoriaRepository);
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
        $idCategoria = elegirCategoria($_POST['nueva_categoria'], $_POST['editar_categoria'], $categoriaRepository);
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

// vistas
// ?var=4&id= editar tarea
if ($usuario->esAdmin() && isset($_GET['var']) && $_GET['var'] == 4 && isset($_GET['id'])) {
    $tarea = $tareaRepository->findById((int) $_GET['id']);
    if ($tarea) {
        $usuarios = $usuarioRepository->findAll();
        $categorias = $categoriaRepository->findAll();
        require __DIR__ . '/../views/editarView.phtml';
        exit;
    }
}

// el admin ve todas, el usuario solo las suyas
// ?orden= columna para ordenar
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
