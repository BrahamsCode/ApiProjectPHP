<?php
require_once '../config.php';
requerirAuth();

$conn = getConnection();

// Obtener estadísticas
$totalProductos = $conn->query("SELECT COUNT(*) as total FROM productos")->fetch()['total'];
$totalPedidos = $conn->query("SELECT COUNT(*) as total FROM pedidos")->fetch()['total'];
$pedidosPendientes = $conn->query("SELECT COUNT(*) as total FROM pedidos WHERE estado = 'pendiente'")->fetch()['total'];
$ventasTotal = $conn->query("SELECT COALESCE(SUM(total), 0) as total FROM pedidos")->fetch()['total'];

// Últimos pedidos
$ultimosPedidos = $conn->query("SELECT * FROM pedidos ORDER BY id DESC LIMIT 5")->fetchAll();

// Productos con bajo stock
$bajoStock = $conn->query("SELECT * FROM productos WHERE stock < 10 ORDER BY stock ASC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Panel Administrativo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar h1 {
            font-size: 24px;
        }
        
        .navbar-menu {
            display: flex;
            gap: 20px;
        }
        
        .navbar-menu a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        
        .navbar-menu a:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .welcome {
            margin-bottom: 30px;
        }
        
        .welcome h2 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .welcome p {
            color: #666;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .stat-card .value {
            font-size: 36px;
            font-weight: bold;
            color: #333;
        }
        
        .stat-card.blue .value { color: #667eea; }
        .stat-card.green .value { color: #28a745; }
        .stat-card.orange .value { color: #ff9800; }
        .stat-card.purple .value { color: #764ba2; }
        
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }
        
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .card h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 20px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #666;
            font-size: 14px;
        }
        
        .table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge.pendiente {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge.completado {
            background: #d4edda;
            color: #155724;
        }
        
        .badge.cancelado {
            background: #f8d7da;
            color: #721c24;
        }
        
        .badge.warning {
            background: #ffebee;
            color: #c62828;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>🛍️ Panel Administrativo</h1>
        <div class="navbar-menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="productos.php">Productos</a>
            <a href="categorias.php">Categorías</a>
            <a href="pedidos.php">Pedidos</a>
            <a href="../index.php" target="_blank">Ver Tienda</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="welcome">
            <h2>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>!</h2>
            <p>Resumen de tu tienda</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card blue">
                <h3>Total Productos</h3>
                <div class="value"><?php echo $totalProductos; ?></div>
            </div>
            
            <div class="stat-card green">
                <h3>Total Pedidos</h3>
                <div class="value"><?php echo $totalPedidos; ?></div>
            </div>
            
            <div class="stat-card orange">
                <h3>Pedidos Pendientes</h3>
                <div class="value"><?php echo $pedidosPendientes; ?></div>
            </div>
            
            <div class="stat-card purple">
                <h3>Ventas Totales</h3>
                <div class="value">$<?php echo number_format($ventasTotal, 2); ?></div>
            </div>
        </div>
        
        <div class="content-grid">
            <div class="card">
                <h3>Últimos Pedidos</h3>
                <?php if (count($ultimosPedidos) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ultimosPedidos as $pedido): ?>
                                <tr>
                                    <td>#<?php echo $pedido['id']; ?></td>
                                    <td><?php echo htmlspecialchars($pedido['cliente_nombre']); ?></td>
                                    <td>$<?php echo number_format($pedido['total'], 2); ?></td>
                                    <td><span class="badge <?php echo $pedido['estado']; ?>"><?php echo ucfirst($pedido['estado']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color: #666;">No hay pedidos aún</p>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <h3>Productos con Bajo Stock</h3>
                <?php if (count($bajoStock) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Stock</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bajoStock as $producto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                    <td><?php echo $producto['stock']; ?></td>
                                    <td>
                                        <?php if ($producto['stock'] < 5): ?>
                                            <span class="badge warning">Crítico</span>
                                        <?php else: ?>
                                            <span class="badge pendiente">Bajo</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color: #666;">Todos los productos tienen stock suficiente</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
