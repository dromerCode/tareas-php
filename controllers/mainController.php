<?php
require_once __DIR__ . '/../db.php';   // deja lista $db con los datos del .env

$page = $_GET['page'] ?? 'tareas';

if ($page == 'login' || $page == 'registro' || $page == 'logout') {
    require __DIR__ . '/authController.php';
} elseif ($page == 'tareas' || $page == 'nueva_tarea' || $page == 'editar_tarea' || $page == 'borrar_tarea' || $page == 'aceptar_tarea' || $page == 'completar_tarea') {
    require __DIR__ . '/tareaController.php';
} elseif ($page == 'usuarios' || $page == 'cambiar_rol' || $page == 'borrar_usuario') {
    require __DIR__ . '/adminController.php';
}
