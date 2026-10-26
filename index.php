<?php
// hay que cargar Usuario antes del session_start
// porque en la sesion guardo el objeto
require_once __DIR__ . '/models/Usuario.php';
session_start();
require_once __DIR__ . '/controllers/mainController.php';
