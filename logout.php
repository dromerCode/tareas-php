<?php
// Recuperamos la sesión actual para poder cerrarla.
session_start();
// Borramos las variables de la sesión y la destruimos.
session_unset();
session_destroy();
// Volvemos a la página principal (que enseñará el login).
header("Location: index.php");
exit;
