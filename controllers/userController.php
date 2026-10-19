<?php
require_once __DIR__ . '/../repositories/UsuarioRepository.php';

$usuarioRepository = new UsuarioRepository($db);

// Se pone a true cuando el registro sale bien, para no volver a enseñar el formulario.
$registrado = false;

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
        $nuevoUsuario = new Usuario(null, $nombre, $email, password_hash($_POST['reg_passwd'], PASSWORD_DEFAULT), 'usuario');
        $usuarioRepository->save($nuevoUsuario);
        $mensaje = "Usuario registrado correctamente";
        $registrado = true;
    }
}

// ---------- LOGIN ----------
if (isset($_POST['email']) && isset($_POST['passwd'])) {
    $usuarioLogin = $usuarioRepository->findByEmail(trim($_POST['email']));

    if ($usuarioLogin && password_verify($_POST['passwd'], $usuarioLogin->getPassword())) {
        // Guardamos el objeto Usuario entero en la sesión: así tenemos su id, nombre y rol
        $_SESSION['usuario'] = $usuarioLogin;
        header("Location: index.php");
        exit;
    } else {
        $mensaje = "Email o contraseña incorrectos";
    }
}

// ---------- VISTAS DE LOGIN Y REGISTRO ----------
// Si nadie ha iniciado sesión: ?var=2 -> registro, si no -> login. Y paramos aquí.
if (!isset($_SESSION['usuario'])) {
    if (isset($_GET['var']) && $_GET['var'] == 2) {
        require __DIR__ . '/../views/registerView.phtml';
    } else {
        require __DIR__ . '/../views/loginView.phtml';
    }
    exit;
}

// A partir de aquí hay alguien logueado: lo dejamos en $usuario para el resto
$usuario = $_SESSION['usuario'];
