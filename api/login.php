<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    enviarJSON(['error' => 'Método no permitido. Use POST'], 405);
}

// Obtener datos del body
$data = json_decode(file_get_contents('php://input'), true);

// Validar que se enviaron los datos
if (!isset($data['username']) || !isset($data['password'])) {
    enviarJSON([
        'error' => 'Datos incompletos',
        'mensaje' => 'Se requieren username y password'
    ], 400);
}

$username = $data['username'];
$password = $data['password'];

try {
    $conn = getConnection();

    // Buscar usuario
    $stmt = $conn->prepare("SELECT id, username, password, email FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $usuario = $stmt->fetch();

    // Verificar usuario y contraseña
    if ($usuario && password_verify($password, $usuario['password'])) {

        // Iniciar sesión
        iniciarSesion();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['username'];

        // Generar token simple (en producción usar JWT)
        $token = bin2hex(random_bytes(32));
        $_SESSION['api_token'] = $token;

        // Respuesta exitosa
        enviarJSON([
            'success' => true,
            'mensaje' => 'Login exitoso',
            'data' => [
                'token' => $token,
                'usuario' => [
                    'id' => $usuario['id'],
                    'username' => $usuario['username'],
                    'email' => $usuario['email']
                ]
            ]
        ], 200);
    } else {
        enviarJSON([
            'error' => 'Credenciales inválidas',
            'mensaje' => 'Usuario o contraseña incorrectos'
        ], 401);
    }
} catch (Exception $e) {
    enviarJSON([
        'error' => 'Error del servidor',
        'mensaje' => 'Ocurrió un error al procesar la solicitud'
    ], 500);
}
