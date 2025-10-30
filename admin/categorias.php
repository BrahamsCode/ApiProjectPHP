<?php
require_once '../config.php';
requerirAuth();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - Panel Administrativo</title>
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
            <h2>Gestión de Categorías</h2>
            <button class="btn btn-primary" onclick="abrirModal()">+ Nueva Categoría</button>
        </div>
        
        <div class="card">
            <div id="loading" class="loading">Cargando categorías...</div>
            <table class="table" id="tablaCategorias" style="display: none;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Productos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="categoriasBody"></tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal para crear/editar categoría -->
    <div id="modalCategoria" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitulo">Nueva Categoría</h3>
            </div>
            <form id="formCategoria">
                <input type="hidden" id="categoriaId">
                
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" id="nombre" required>
                </div>
                
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea id="descripcion"></textarea>
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
        const API_URL = '../api/categorias.php';
        let editandoId = null;
        
        // Cargar categorías al iniciar
        cargarCategorias();
        
        function cargarCategorias() {
            document.getElementById('loading').style.display = 'block';
            document.getElementById('tablaCategorias').style.display = 'none';
            
            fetch(API_URL)
                .then(res => res.json())
                .then(categorias => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('tablaCategorias').style.display = 'table';
                    
                    const tbody = document.getElementById('categoriasBody');
                    tbody.innerHTML = '';
                    
                    categorias.forEach(categoria => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>#${categoria.id}</td>
                            <td>${categoria.nombre}</td>
                            <td>${categoria.descripcion || '-'}</td>
                            <td>${categoria.total_productos}</td>
                            <td><span class="badge ${categoria.activo == 1 ? 'activo' : 'inactivo'}">${categoria.activo == 1 ? 'Activo' : 'Inactivo'}</span></td>
                            <td>
                                <button class="btn btn-primary btn-small" onclick="editarCategoria(${categoria.id})">Editar</button>
                                <button class="btn btn-danger btn-small" onclick="eliminarCategoria(${categoria.id})" ${categoria.total_productos > 0 ? 'disabled title="No se puede eliminar (tiene productos)"' : ''}>Eliminar</button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar categorías');
                });
        }
        
        function abrirModal() {
            editandoId = null;
            document.getElementById('modalTitulo').textContent = 'Nueva Categoría';
            document.getElementById('formCategoria').reset();
            document.getElementById('categoriaId').value = '';
            document.getElementById('modalCategoria').classList.add('active');
        }
        
        function cerrarModal() {
            document.getElementById('modalCategoria').classList.remove('active');
        }
        
        function editarCategoria(id) {
            fetch(`${API_URL}?id=${id}`)
                .then(res => res.json())
                .then(categoria => {
                    editandoId = id;
                    document.getElementById('modalTitulo').textContent = 'Editar Categoría';
                    document.getElementById('categoriaId').value = categoria.id;
                    document.getElementById('nombre').value = categoria.nombre;
                    document.getElementById('descripcion').value = categoria.descripcion;
                    document.getElementById('activo').value = categoria.activo;
                    document.getElementById('modalCategoria').classList.add('active');
                });
        }
        
        function eliminarCategoria(id) {
            if (confirm('¿Estás seguro de eliminar esta categoría?')) {
                fetch(`${API_URL}?id=${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.mensaje);
                    } else {
                        alert(data.mensaje);
                        cargarCategorias();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar categoría');
                });
            }
        }
        
        document.getElementById('formCategoria').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const data = {
                nombre: document.getElementById('nombre').value,
                descripcion: document.getElementById('descripcion').value,
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
                cargarCategorias();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar categoría');
            });
        });
        
        // Cerrar modal al hacer clic fuera
        document.getElementById('modalCategoria').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });
    </script>
</body>
</html>
