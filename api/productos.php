<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$metodo = $_SERVER['REQUEST_METHOD'];
$conn = getConnection();

// Obtener el ID si existe en la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

switch ($metodo) {
    case 'GET':
        if ($id) {
            // Obtener un producto específico con su categoría
            $stmt = $conn->prepare("SELECT p.*, c.nombre as categoria_nombre 
                                   FROM productos p 
                                   LEFT JOIN categorias c ON p.categoria_id = c.id 
                                   WHERE p.id = ?");
            $stmt->execute([$id]);
            $producto = $stmt->fetch();

            if ($producto) {
                enviarJSON($producto);
            } else {
                enviarJSON(['error' => 'Producto no encontrado'], 404);
            }
        } else {
            // Obtener todos los productos con sus categorías
            $activo = isset($_GET['activo']) ? intval($_GET['activo']) : null;
            $categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : null;

            $query = "SELECT p.*, c.nombre as categoria_nombre 
                     FROM productos p 
                     LEFT JOIN categorias c ON p.categoria_id = c.id";
            $params = [];
            $conditions = [];

            if ($activo !== null) {
                $conditions[] = "p.activo = ?";
                $params[] = $activo;
            }

            if ($categoria_id !== null) {
                $conditions[] = "p.categoria_id = ?";
                $params[] = $categoria_id;
            }

            if (!empty($conditions)) {
                $query .= " WHERE " . implode(" AND ", $conditions);
            }

            $query .= " ORDER BY p.id DESC";

            if (!empty($params)) {
                $stmt = $conn->prepare($query);
                $stmt->execute($params);
            } else {
                $stmt = $conn->query($query);
            }

            $productos = $stmt->fetchAll();
            enviarJSON($productos);
        }
        break;

    case 'POST':
        // Crear nuevo producto
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['nombre']) || !isset($data['precio'])) {
            enviarJSON(['error' => 'Datos incompletos'], 400);
        }

        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, imagen, activo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['descripcion'] ?? '',
            $data['precio'],
            $data['stock'] ?? 0,
            $data['categoria_id'] ?? null,
            $data['imagen'] ?? '',
            $data['activo'] ?? 1
        ]);

        $nuevoId = $conn->lastInsertId();
        enviarJSON(['id' => $nuevoId, 'mensaje' => 'Producto creado exitosamente'], 201);
        break;

    case 'PUT':
        // Actualizar producto
        if (!$id) {
            enviarJSON(['error' => 'ID no proporcionado'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $stmt = $conn->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, categoria_id = ?, imagen = ?, activo = ? WHERE id = ?");
        $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
            $data['categoria_id'] ?? null,
            $data['imagen'] ?? '',
            $data['activo'] ?? 1,
            $id
        ]);

        enviarJSON(['mensaje' => 'Producto actualizado exitosamente']);
        break;

    case 'DELETE':
        // Eliminar producto
        if (!$id) {
            enviarJSON(['error' => 'ID no proporcionado'], 400);
        }

        $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->execute([$id]);

        enviarJSON(['mensaje' => 'Producto eliminado exitosamente']);
        break;

    default:
        enviarJSON(['error' => 'Método no permitido'], 405);
        break;
}
