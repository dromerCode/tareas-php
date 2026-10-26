<?php
// la clase antes del session_start
require_once __DIR__ . '/models/Usuario.php';
session_start();
session_unset();
session_destroy();
header("Location: index.php");
exit;
