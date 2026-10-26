<?php

// conexion a la bd
require_once __DIR__ . '/../db.php';

// mensaje de error o de ok
$mensaje = "";

// registro y login
require __DIR__ . '/userController.php';

// tareas (aqui ya hay sesion)
require __DIR__ . '/taskController.php';
