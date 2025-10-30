<?php
require_once '../config.php';
iniciarSesion();

// Destruir la sesión
session_destroy();

// Redirigir al login
header('Location: login.php');
exit();
?>
