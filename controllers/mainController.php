<?php

// Cargamos la conexión ($db), que usan los dos controladores.
require_once __DIR__ . '/../db.php';

// Aquí guardaremos el mensaje que se mostrará al usuario (error, éxito...).
$mensaje = "";

// Primero lo de los usuarios: registro, login y, si nadie ha iniciado sesión, sus vistas.
require __DIR__ . '/userController.php';

// Si llegamos aquí es que hay alguien logueado: todo lo de las tareas.
require __DIR__ . '/taskController.php';
