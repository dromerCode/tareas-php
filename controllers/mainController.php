<?php

// Cargamos la conexión ($db) y los repositorios, que a su vez cargan los modelos (Usuario, Tarea).
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../repositories/UsuarioRepository.php';
require_once __DIR__ . '/../repositories/TareaRepository.php';

$usuarioRepository = new UsuarioRepository($db);
$tareaRepository = new TareaRepository($db);

// Aquí guardaremos el mensaje que se mostrará al usuario (error, éxito...).
$mensaje = "";
// Se pone a true cuando el registro sale bien, para no volver a enseñar el formulario.
$registrado = false;

// Para no repetir lo mismo en cada sección: ¿hay alguien logueado? ¿es admin?
$logueado = isset($_SESSION['id_usuario']);
$esAdmin = $logueado && $_SESSION['rol'] == 'admin';

// ---------- REGISTRO ----------
if (isset($_POST['reg_nombre']) && isset($_POST['reg_email']) && isset($_POST['reg_passwd'])) {
    $nombre = trim($_POST['reg_nombre']);
    $email = trim($_POST['reg_email']);

    if ($nombre == "" || $email == "" || $_POST['reg_passwd'] == "") {
        $mensaje = "Rellena todos los campos";
    } elseif ($usuarioRepository->findByEmail($email)) {
        $mensaje = "Ese email ya está registrado";
    } else {
        // El id es null porque lo pone la base de datos. La contraseña se guarda cifrada.
        $usuario = new Usuario(null, $nombre, $email, password_hash($_POST['reg_passwd'], PASSWORD_DEFAULT), 'usuario');
        $usuarioRepository->save($usuario);
        $mensaje = "Usuario registrado correctamente";
        $registrado = true;
    }
}

// ---------- LOGIN ----------
if (isset($_POST['email']) && isset($_POST['passwd'])) {
    $usuario = $usuarioRepository->findByEmail(trim($_POST['email']));

    if ($usuario && password_verify($_POST['passwd'], $usuario->getPassword())) {
        // Guardamos en la sesión quién es y su rol
        $_SESSION['id_usuario'] = $usuario->getId();
        $_SESSION['nombre'] = $usuario->getNombre();
        $_SESSION['rol'] = $usuario->getRol();
        header("Location: index.php");
        exit;
    } else {
        $mensaje = "Email o contraseña incorrectos";
    }
}

// ---------- ACEPTAR Y COMPLETAR (usuario) ----------
// Solo se puede cambiar el estado de una tarea que tienes asignada tú.
if ($logueado && isset($_POST['aceptar'])) {
    $tarea = $tareaRepository->findById((int) $_POST['aceptar']);
    if ($tarea && $tarea->getIdUsuario() == $_SESSION['id_usuario']) {
        $tareaRepository->cambiarEstado($tarea->getId(), 'aceptada');
    }
    header("Location: index.php");
    exit;
}

if ($logueado && isset($_POST['completar'])) {
    $tarea = $tareaRepository->findById((int) $_POST['completar']);
    if ($tarea && $tarea->getIdUsuario() == $_SESSION['id_usuario']) {
        $tareaRepository->cambiarEstado($tarea->getId(), 'completada');
    }
    header("Location: index.php");
    exit;
}

// ---------- CREAR, EDITAR Y BORRAR (solo admin) ----------
if ($esAdmin && isset($_POST['crear_titulo'])) {
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

if ($esAdmin && isset($_POST['editar_id'])) {
    if (trim($_POST['editar_titulo']) == "") {
        $mensaje = "El título no puede estar vacío";
    } else {
        $tarea = new Tarea((int) $_POST['editar_id'], trim($_POST['editar_titulo']), trim($_POST['editar_descripcion']), $_POST['editar_estado'], (int) $_POST['editar_usuario'], null);
        $tareaRepository->update($tarea);
        header("Location: index.php");
        exit;
    }
}

if ($esAdmin && isset($_POST['borrar'])) {
    $tareaRepository->delete((int) $_POST['borrar']);
    header("Location: index.php");
    exit;
}

// ---------- VISTAS ----------
// Si nadie ha iniciado sesión: ?var=2 -> registro, si no -> login.
if (!$logueado) {
    if (isset($_GET['var']) && $_GET['var'] == 2) {
        require __DIR__ . '/../views/registerView.phtml';
    } else {
        require __DIR__ . '/../views/loginView.phtml';
    }
    exit;
}

// ?var=4&id=... -> el admin edita esa tarea
if ($esAdmin && isset($_GET['var']) && $_GET['var'] == 4) {
    $tarea = $tareaRepository->findById((int) $_GET['id']);
    if ($tarea) {
        $usuarios = $usuarioRepository->findAll();
        require __DIR__ . '/../views/editarView.phtml';
        exit;
    }
}

// Página principal: el admin ve todas las tareas, el usuario solo las suyas.
if ($esAdmin) {
    $tareas = $tareaRepository->findAll();
    $usuarios = $usuarioRepository->findAll();
} else {
    $tareas = $tareaRepository->findByUsuario($_SESSION['id_usuario']);
}
require __DIR__ . '/../views/mainView.phtml';
