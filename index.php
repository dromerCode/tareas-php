<?php
// La clase Usuario tiene que estar cargada ANTES de session_start(),
// porque en la sesión guardamos un objeto Usuario y PHP necesita la clase para recuperarlo.
require_once __DIR__ . '/models/Usuario.php';
session_start();
require_once __DIR__ . '/controllers/mainController.php';
