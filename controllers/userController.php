<?php
require_once __DIR__ . '/../repositories/UsuarioRepository.php';

$usuarioRepository = new UsuarioRepository($db);

// true si el registro sale bien
$registrado = false;

// registro
if (isset($_POST['reg_nombre']) && isset($_POST['reg_email']) && isset($_POST['reg_passwd'])) {
    $nombre = trim($_POST['reg_nombre']);
    $email = trim($_POST['reg_email']);

    if ($nombre == "" || $email == "" || $_POST['reg_passwd'] == "") {
        $mensaje = "Rellena todos los campos";
    } elseif ($usuarioRepository->findByEmail($email)) {
        $mensaje = "Ese email ya está registrado";
    } else {
        // la contraseña cifrada
        $nuevoUsuario = new Usuario(null, $nombre, $email, password_hash($_POST['reg_passwd'], PASSWORD_DEFAULT), 'usuario');
        $usuarioRepository->save($nuevoUsuario);
        $mensaje = "Usuario registrado correctamente";
        $registrado = true;
    }
}

// login
if (isset($_POST['email']) && isset($_POST['passwd'])) {
    $usuarioLogin = $usuarioRepository->findByEmail(trim($_POST['email']));

    if ($usuarioLogin && password_verify($_POST['passwd'], $usuarioLogin->getPassword())) {
        // guardo el usuario en la sesion
        $_SESSION['usuario'] = $usuarioLogin;
        header("Location: index.php");
        exit;
    } else {
        $mensaje = "Email o contraseña incorrectos";
    }
}

// vistas de login y registro
// si no hay sesion: ?var=2 registro, si no login
if (!isset($_SESSION['usuario'])) {
    if (isset($_GET['var']) && $_GET['var'] == 2) {
        require __DIR__ . '/../views/registerView.phtml';
    } else {
        require __DIR__ . '/../views/loginView.phtml';
    }
    exit;
}

// aqui ya hay alguien logueado
$usuario = $_SESSION['usuario'];
