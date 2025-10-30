<?php
require_once '../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');

$metodo = $_SERVER['REQUEST_METHOD'];
$conn = getConnection();

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

switch($metodo) {
    case 'GET':
        if ($id) {
            // Obtener un pedido específico con sus items
            $stmt = $conn->prepare("SELECT * FROM pedidos WHERE id = ?");
            $stmt->execute([$id]);
            $pedido = $stmt->fetch();
            
            if ($pedido) {
                // Obtener items del pedido
                $stmt = $conn->prepare("
                    SELECT pi.*, p.nombre as producto_nombre 
                    FROM pedido_items pi 
                    JOIN productos p ON pi.producto_id = p.id 
                    WHERE pi.pedido_id = ?
                ");
                $stmt->execute([$id]);
                $pedido['items'] = $stmt->fetchAll();
                
                enviarJSON($pedido);
            } else {
                enviarJSON(['error' => 'Pedido no encontrado'], 404);
            }
        } else {
            // Obtener todos los pedidos
            $stmt = $conn->query("SELECT * FROM pedidos ORDER BY id DESC");
            $pedidos = $stmt->fetchAll();
            enviarJSON($pedidos);
        }
        break;
        
    case 'POST':
        // Crear nuevo pedido
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['cliente_nombre']) || !isset($data['items'])) {
            enviarJSON(['error' => 'Datos incompletos'], 400);
        }
        
        $conn->beginTransaction();
        
        try {
            // Insertar pedido
            $stmt = $conn->prepare("INSERT INTO pedidos (cliente_nombre, cliente_email, total, estado) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $data['cliente_nombre'],
                $data['cliente_email'] ?? '',
                $data['total'],
                'pendiente'
            ]);
            
            $pedidoId = $conn->lastInsertId();
            
            // Insertar items del pedido
            $stmt = $conn->prepare("INSERT INTO pedido_items (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
            
            foreach ($data['items'] as $item) {
                $stmt->execute([
                    $pedidoId,
                    $item['producto_id'],
                    $item['cantidad'],
                    $item['precio']
                ]);
                
                // Actualizar stock
                $stmtStock = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
                $stmtStock->execute([$item['cantidad'], $item['producto_id']]);
            }
            
            $conn->commit();
            enviarJSON(['id' => $pedidoId, 'mensaje' => 'Pedido creado exitosamente'], 201);
            
        } catch (Exception $e) {
            $conn->rollBack();
            enviarJSON(['error' => 'Error al crear pedido: ' . $e->getMessage()], 500);
        }
        break;
        
    case 'PUT':
        // Actualizar estado del pedido
        if (!$id) {
            enviarJSON(['error' => 'ID no proporcionado'], 400);
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $conn->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
        $stmt->execute([$data['estado'], $id]);
        
        enviarJSON(['mensaje' => 'Estado del pedido actualizado']);
        break;
        
    default:
        enviarJSON(['error' => 'Método no permitido'], 405);
        break;
}
?>
