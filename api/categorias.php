<?php
require_once '../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$metodo = $_SERVER['REQUEST_METHOD'];
$conn = getConnection();

// Obtener el ID si existe en la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

switch($metodo) {
    case 'GET':
        if ($id) {
            // Obtener una categoría específica
            $stmt = $conn->prepare("SELECT * FROM categorias WHERE id = ?");
            $stmt->execute([$id]);
            $categoria = $stmt->fetch();
            
            if ($categoria) {
                // Obtener productos de esta categoría
                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM productos WHERE categoria_id = ?");
                $stmt->execute([$id]);
                $categoria['total_productos'] = $stmt->fetch()['total'];
                
                enviarJSON($categoria);
            } else {
                enviarJSON(['error' => 'Categoría no encontrada'], 404);
            }
        } else {
            // Obtener todas las categorías
            $activo = isset($_GET['activo']) ? intval($_GET['activo']) : null;
            
            if ($activo !== null) {
                $stmt = $conn->prepare("SELECT c.*, COUNT(p.id) as total_productos 
                                       FROM categorias c 
                                       LEFT JOIN productos p ON c.id = p.categoria_id 
                                       WHERE c.activo = ? 
                                       GROUP BY c.id 
                                       ORDER BY c.id DESC");
                $stmt->execute([$activo]);
            } else {
                $stmt = $conn->query("SELECT c.*, COUNT(p.id) as total_productos 
                                     FROM categorias c 
                                     LEFT JOIN productos p ON c.id = p.categoria_id 
                                     GROUP BY c.id 
                                     ORDER BY c.id DESC");
            }
            
            $categorias = $stmt->fetchAll();
            enviarJSON($categorias);
        }
        break;
        
    case 'POST':
        // Crear nueva categoría
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['nombre'])) {
            enviarJSON(['error' => 'El nombre es requerido'], 400);
        }
        
        $stmt = $conn->prepare("INSERT INTO categorias (nombre, descripcion, activo) VALUES (?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['descripcion'] ?? '',
            $data['activo'] ?? 1
        ]);
        
        $nuevoId = $conn->lastInsertId();
        enviarJSON([
            'id' => $nuevoId,
            'mensaje' => 'Categoría creada exitosamente'
        ], 201);
        break;
        
    case 'PUT':
        // Actualizar categoría
        if (!$id) {
            enviarJSON(['error' => 'ID no proporcionado'], 400);
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $conn->prepare("UPDATE categorias SET nombre = ?, descripcion = ?, activo = ? WHERE id = ?");
        $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['activo'] ?? 1,
            $id
        ]);
        
        enviarJSON(['mensaje' => 'Categoría actualizada exitosamente']);
        break;
        
    case 'DELETE':
        // Eliminar categoría
        if (!$id) {
            enviarJSON(['error' => 'ID no proporcionado'], 400);
        }
        
        // Verificar si tiene productos asociados
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM productos WHERE categoria_id = ?");
        $stmt->execute([$id]);
        $total = $stmt->fetch()['total'];
        
        if ($total > 0) {
            enviarJSON([
                'error' => 'No se puede eliminar',
                'mensaje' => 'La categoría tiene productos asociados'
            ], 400);
        }
        
        $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        
        enviarJSON(['mensaje' => 'Categoría eliminada exitosamente']);
        break;
        
    default:
        enviarJSON(['error' => 'Método no permitido'], 405);
        break;
}
?>
