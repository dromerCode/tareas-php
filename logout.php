<?php
// Igual que en index.php: la clase antes de session_start() para recuperar el objeto Usuario.
require_once __DIR__ . '/models/Usuario.php';
session_start();
// Borramos las variables de la sesión y la destruimos.
session_unset();
session_destroy();
// Volvemos a la página principal (que enseñará el login).
header("Location: index.php");
exit;
