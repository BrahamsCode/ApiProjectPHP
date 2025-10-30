<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'tienda_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Crear conexión
function getConnection() {
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    } catch(PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

// Iniciar sesión si no está iniciada
function iniciarSesion() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Verificar si el usuario está autenticado
function estaAutenticado() {
    iniciarSesion();
    return isset($_SESSION['usuario_id']);
}

// Requerir autenticación
function requerirAuth() {
    if (!estaAutenticado()) {
        header('Location: /admin/login.php');
        exit();
    }
}

// Función para enviar respuesta JSON
function enviarJSON($data, $codigo = 200) {
    http_response_code($codigo);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}
?>