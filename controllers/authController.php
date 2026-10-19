<?php
require_once __DIR__ . '/../repositories/UsuarioRepository.php';
$usuarioRepository = new UsuarioRepository($db);
$mensaje = '';
$registrado = false;
$page = $_GET['page'] ?? 'login'; // Por defecto, mostramos la página de login
if ($page === 'registro' && isset($_POST['email'])) {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    // La contraseña sin trim: los espacios pueden formar parte de ella
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    if ($nombre == "" || $apellido == "" || $email == "" || $_POST['contrasena'] == "") {
        $mensaje = 'Rellena todos los campos';
    } elseif ($usuarioRepository->findByEmail($email)) {
        $mensaje = 'Ese email ya está registrado';
    } else {
        $usuario = new Usuario(null, $nombre, $apellido, $email, $contrasena);
        $usuarioRepository->save($usuario);
        $mensaje = 'Registro exitoso. Ahora puedes iniciar sesión.';
        $registrado = true;
    }
}

if ($page === 'login' && isset($_POST['email'])) {
    $usuario = $usuarioRepository->findByEmail(trim($_POST['email']));

    // Mismo mensaje en los dos casos para no revelar qué emails están registrados
    if (!$usuario) {
        $mensaje = 'Email o contraseña incorrectos';
    } elseif (!password_verify($_POST['contrasena'], $usuario->getPassword())) {
        $mensaje = 'Email o contraseña incorrectos';
    } else {
        // El id hace falta para saber qué tareas son suyas
        $_SESSION['id_usuario'] = $usuario->getIdUsuario();
        $_SESSION['nombre'] = $usuario->getNombre();
        // El rol decide qué puede hacer: el admin gestiona todas las tareas y los usuarios
        $_SESSION['rol'] = $usuario->getRol();
        header("Location: index.php");
        exit;
    }
}

if ($page === 'logout') {
    session_unset();
    session_destroy();
    header("Location: index.php?page=login");
    exit;
}

if ($page === 'registro') {
    require __DIR__ . '/../views/registerView.phtml';
} else {
    require __DIR__ . '/../views/loginView.phtml';
}
