<?php
require_once __DIR__ . '/../config.php';
requerirAuth();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - Panel Administrativo</title>
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            background: rgba(255, 255, 255, 0.2);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h2 {
            color: #333;
            font-size: 32px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            margin-bottom: 20px;
        }

        .modal-header h3 {
            color: #333;
            font-size: 24px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
        }

        .items-list {
            margin: 20px 0;
        }

        .item {
            padding: 10px;
            background: #f8f9fa;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        select {
            padding: 8px;
            border: 2px solid #e1e8ed;
            border-radius: 5px;
            font-size: 14px;
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
        <div class="header">
            <h2>Gestión de Pedidos</h2>
        </div>

        <div class="card">
            <div id="loading" class="loading">Cargando pedidos...</div>
            <table class="table" id="tablaPedidos" style="display: none;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="pedidosBody"></tbody>
            </table>
        </div>
    </div>

    <!-- Modal para ver detalle del pedido -->
    <div id="modalPedido" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detalle del Pedido #<span id="pedidoId"></span></h3>
            </div>

            <div id="pedidoDetalle"></div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="cerrarModal()">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '../api/pedidos.php';

        // Cargar pedidos al iniciar
        cargarPedidos();

        function cargarPedidos() {
            document.getElementById('loading').style.display = 'block';
            document.getElementById('tablaPedidos').style.display = 'none';

            fetch(API_URL)
                .then(res => res.json())
                .then(pedidos => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('tablaPedidos').style.display = 'table';

                    const tbody = document.getElementById('pedidosBody');
                    tbody.innerHTML = '';

                    pedidos.forEach(pedido => {
                        const tr = document.createElement('tr');
                        const fecha = new Date(pedido.created_at).toLocaleDateString('es-ES');

                        tr.innerHTML = `
                            <td>#${pedido.id}</td>
                            <td>${pedido.cliente_nombre}</td>
                            <td>${pedido.cliente_email}</td>
                            <td>$${parseFloat(pedido.total).toFixed(2)}</td>
                            <td><span class="badge ${pedido.estado}">${pedido.estado.charAt(0).toUpperCase() + pedido.estado.slice(1)}</span></td>
                            <td>${fecha}</td>
                            <td>
                                <button class="btn btn-primary" onclick="verDetalle(${pedido.id})">Ver Detalle</button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar pedidos');
                });
        }

        function verDetalle(id) {
            fetch(`${API_URL}?id=${id}`)
                .then(res => res.json())
                .then(pedido => {
                    document.getElementById('pedidoId').textContent = pedido.id;

                    let itemsHTML = '<div class="items-list"><h4>Productos:</h4>';
                    pedido.items.forEach(item => {
                        itemsHTML += `
                            <div class="item">
                                <strong>${item.producto_nombre}</strong><br>
                                Cantidad: ${item.cantidad} x $${parseFloat(item.precio).toFixed(2)} = $${(item.cantidad * item.precio).toFixed(2)}
                            </div>
                        `;
                    });
                    itemsHTML += '</div>';

                    const detalle = `
                        <div class="detail-row">
                            <span class="detail-label">Cliente:</span>
                            <span>${pedido.cliente_nombre}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Email:</span>
                            <span>${pedido.cliente_email}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Total:</span>
                            <span><strong>$${parseFloat(pedido.total).toFixed(2)}</strong></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Estado:</span>
                            <span>
                                <select id="estadoPedido" onchange="cambiarEstado(${pedido.id})">
                                    <option value="pendiente" ${pedido.estado === 'pendiente' ? 'selected' : ''}>Pendiente</option>
                                    <option value="completado" ${pedido.estado === 'completado' ? 'selected' : ''}>Completado</option>
                                    <option value="cancelado" ${pedido.estado === 'cancelado' ? 'selected' : ''}>Cancelado</option>
                                </select>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Fecha:</span>
                            <span>${new Date(pedido.created_at).toLocaleString('es-ES')}</span>
                        </div>
                        ${itemsHTML}
                    `;

                    document.getElementById('pedidoDetalle').innerHTML = detalle;
                    document.getElementById('modalPedido').classList.add('active');
                });
        }

        function cambiarEstado(id) {
            const nuevoEstado = document.getElementById('estadoPedido').value;

            fetch(`${API_URL}?id=${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        estado: nuevoEstado
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.mensaje);
                    cargarPedidos();
                });
        }

        function cerrarModal() {
            document.getElementById('modalPedido').classList.remove('active');
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('modalPedido').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });
    </script>
</body>

</html>