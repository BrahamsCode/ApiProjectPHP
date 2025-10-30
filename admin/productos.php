<?php
require_once __DIR__ . '/../config.php';
requerirAuth();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Panel Administrativo</title>
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
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .header h2 {
            color: #333;
            font-size: 32px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
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
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        
        .badge.activo {
            background: #d4edda;
            color: #155724;
        }
        
        .badge.inactivo {
            background: #f8d7da;
            color: #721c24;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
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
            max-width: 500px;
        }
        
        .modal-header {
            margin-bottom: 20px;
        }
        
        .modal-header h3 {
            color: #333;
            font-size: 24px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e1e8ed;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }
        
        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
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
            <h2>Gestión de Productos</h2>
            <button class="btn btn-primary" onclick="abrirModal()">+ Nuevo Producto</button>
        </div>
        
        <div class="card">
            <div id="loading" class="loading">Cargando productos...</div>
            <table class="table" id="tablaProductos" style="display: none;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="productosBody"></tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal para crear/editar producto -->
    <div id="modalProducto" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitulo">Nuevo Producto</h3>
            </div>
            <form id="formProducto">
                <input type="hidden" id="productoId">
                
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" id="nombre" required>
                </div>
                
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea id="descripcion"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Precio *</label>
                    <input type="number" id="precio" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label>Stock *</label>
                    <input type="number" id="stock" required>
                </div>
                
                <div class="form-group">
                    <label>Categoría</label>
                    <select id="categoria_id">
                        <option value="">Sin categoría</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Imagen (URL)</label>
                    <input type="text" id="imagen">
                </div>
                
                <div class="form-group">
                    <label>Estado</label>
                    <select id="activo">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn" onclick="cerrarModal()">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const API_URL = '../api/productos.php';
        const API_CATEGORIAS = '../api/categorias.php';
        let editandoId = null;
        let categorias = [];
        
        // Cargar productos y categorías al iniciar
        cargarCategorias();
        cargarProductos();
        
        function cargarCategorias() {
            fetch(API_CATEGORIAS)
                .then(res => res.json())
                .then(data => {
                    categorias = data;
                    llenarSelectCategorias();
                })
                .catch(error => console.error('Error:', error));
        }
        
        function llenarSelectCategorias() {
            const select = document.getElementById('categoria_id');
            select.innerHTML = '<option value="">Sin categoría</option>';
            
            categorias.forEach(cat => {
                const option = document.createElement('option');
                option.value = cat.id;
                option.textContent = cat.nombre;
                select.appendChild(option);
            });
        }
        
        function cargarProductos() {
            document.getElementById('loading').style.display = 'block';
            document.getElementById('tablaProductos').style.display = 'none';
            
            fetch(API_URL)
                .then(res => res.json())
                .then(productos => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('tablaProductos').style.display = 'table';
                    
                    const tbody = document.getElementById('productosBody');
                    tbody.innerHTML = '';
                    
                    productos.forEach(producto => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>#${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td>$${parseFloat(producto.precio).toFixed(2)}</td>
                            <td>${producto.stock}</td>
                            <td><span class="badge ${producto.activo == 1 ? 'activo' : 'inactivo'}">${producto.activo == 1 ? 'Activo' : 'Inactivo'}</span></td>
                            <td>
                                <button class="btn btn-primary btn-small" onclick="editarProducto(${producto.id})">Editar</button>
                                <button class="btn btn-danger btn-small" onclick="eliminarProducto(${producto.id})">Eliminar</button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar productos');
                });
        }
        
        function abrirModal() {
            editandoId = null;
            document.getElementById('modalTitulo').textContent = 'Nuevo Producto';
            document.getElementById('formProducto').reset();
            document.getElementById('productoId').value = '';
            document.getElementById('modalProducto').classList.add('active');
        }
        
        function cerrarModal() {
            document.getElementById('modalProducto').classList.remove('active');
        }
        
        function editarProducto(id) {
            fetch(`${API_URL}?id=${id}`)
                .then(res => res.json())
                .then(producto => {
                    editandoId = id;
                    document.getElementById('modalTitulo').textContent = 'Editar Producto';
                    document.getElementById('productoId').value = producto.id;
                    document.getElementById('nombre').value = producto.nombre;
                    document.getElementById('descripcion').value = producto.descripcion;
                    document.getElementById('precio').value = producto.precio;
                    document.getElementById('stock').value = producto.stock;
                    document.getElementById('categoria_id').value = producto.categoria_id || '';
                    document.getElementById('imagen').value = producto.imagen;
                    document.getElementById('activo').value = producto.activo;
                    document.getElementById('modalProducto').classList.add('active');
                });
        }
        
        function eliminarProducto(id) {
            if (confirm('¿Estás seguro de eliminar este producto?')) {
                fetch(`${API_URL}?id=${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.mensaje);
                    cargarProductos();
                });
            }
        }
        
        document.getElementById('formProducto').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const data = {
                nombre: document.getElementById('nombre').value,
                descripcion: document.getElementById('descripcion').value,
                precio: document.getElementById('precio').value,
                stock: document.getElementById('stock').value,
                categoria_id: document.getElementById('categoria_id').value || null,
                imagen: document.getElementById('imagen').value,
                activo: document.getElementById('activo').value
            };
            
            const url = editandoId ? `${API_URL}?id=${editandoId}` : API_URL;
            const method = editandoId ? 'PUT' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(data => {
                alert(data.mensaje);
                cerrarModal();
                cargarProductos();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar producto');
            });
        });
        
        // Cerrar modal al hacer clic fuera
        document.getElementById('modalProducto').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });
    </script>
</body>
</html>
