<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>
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
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 32px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cart-button {
            position: relative;
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .cart-button:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .hero {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .hero h2 {
            color: #333;
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .hero p {
            color: #666;
            font-size: 18px;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-name {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .product-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            height: 40px;
            overflow: hidden;
        }
        
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .product-price {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        
        .product-stock {
            font-size: 12px;
            color: #666;
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
        
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        
        .cart-item-info {
            flex: 1;
        }
        
        .cart-item-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .cart-item-price {
            color: #666;
            font-size: 14px;
        }
        
        .cart-item-quantity {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .quantity-btn {
            width: 30px;
            height: 30px;
            border: none;
            background: #667eea;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .cart-total {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            text-align: right;
        }
        
        .cart-total h4 {
            font-size: 24px;
            color: #333;
        }
        
        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
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
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 2px solid #e1e8ed;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .empty-cart {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>🛍️ Mi Tienda Online</h1>
            <div class="cart-button" onclick="abrirCarrito()">
                🛒 Carrito
                <span class="cart-count" id="cartCount">0</span>
            </div>
        </div>
    </header>
    
    <div class="container">
        <div class="hero">
            <h2>Bienvenido a nuestra tienda</h2>
            <p>Los mejores productos al mejor precio</p>
        </div>
        
        <div style="margin-bottom: 30px; text-align: center;">
            <button class="btn" onclick="filtrarCategoria(null)" style="background: #333;">Todas</button>
            <span id="categoriasBotones"></span>
        </div>
        
        <div id="loading" class="loading">Cargando productos...</div>
        <div class="products-grid" id="productsGrid"></div>
    </div>
    
    <!-- Modal del carrito -->
    <div id="modalCarrito" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tu Carrito de Compras</h3>
            </div>
            
            <div id="carritoContenido"></div>
            
            <div class="modal-footer">
                <button type="button" class="btn" onclick="cerrarCarrito()">Seguir Comprando</button>
                <button type="button" class="btn btn-success" id="btnCheckout" onclick="mostrarCheckout()">Finalizar Compra</button>
            </div>
        </div>
    </div>
    
    <!-- Modal de checkout -->
    <div id="modalCheckout" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Finalizar Compra</h3>
            </div>
            
            <div id="mensajeExito" class="success-message" style="display: none;">
                ¡Pedido realizado con éxito! Gracias por tu compra.
            </div>
            
            <form id="formCheckout">
                <div class="form-group">
                    <label>Nombre completo *</label>
                    <input type="text" id="clienteNombre" required>
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" id="clienteEmail" required>
                </div>
                
                <div class="cart-total">
                    <h4>Total: $<span id="totalCheckout">0.00</span></h4>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn" onclick="cerrarCheckout()">Cancelar</button>
                    <button type="submit" class="btn btn-success">Confirmar Pedido</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const API_PRODUCTOS = 'api/productos.php';
        const API_PEDIDOS = 'api/pedidos.php';
        const API_CATEGORIAS = 'api/categorias.php';
        
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        let productos = [];
        let categorias = [];
        let categoriaActual = null;
        
        // Cargar categorías y productos
        cargarCategorias();
        cargarProductos();
        actualizarContadorCarrito();
        
        function cargarCategorias() {
            fetch(API_CATEGORIAS + '?activo=1')
                .then(res => res.json())
                .then(data => {
                    categorias = data;
                    mostrarBotonesCategorias(data);
                })
                .catch(error => console.error('Error al cargar categorías:', error));
        }
        
        function mostrarBotonesCategorias(categorias) {
            const container = document.getElementById('categoriasBotones');
            container.innerHTML = '';
            
            categorias.forEach(cat => {
                const btn = document.createElement('button');
                btn.className = 'btn';
                btn.textContent = cat.nombre;
                btn.onclick = () => filtrarCategoria(cat.id);
                container.appendChild(btn);
            });
        }
        
        function filtrarCategoria(id) {
            categoriaActual = id;
            cargarProductos();
        }
        
        function cargarProductos() {
            let url = API_PRODUCTOS + '?activo=1';
            if (categoriaActual) {
                url += '&categoria_id=' + categoriaActual;
            }
            
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    productos = data;
                    document.getElementById('loading').style.display = 'none';
                    mostrarProductos(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loading').innerHTML = 'Error al cargar productos';
                });
        }
        
        function mostrarProductos(productos) {
            const grid = document.getElementById('productsGrid');
            grid.innerHTML = '';
            
            productos.forEach(producto => {
                const card = document.createElement('div');
                card.className = 'product-card';
                card.innerHTML = `
                    <div class="product-image">📦</div>
                    <div class="product-info">
                        <div class="product-name">${producto.nombre}</div>
                        <div class="product-description">${producto.descripcion || 'Sin descripción'}</div>
                        <div class="product-footer">
                            <div>
                                <div class="product-price">$${parseFloat(producto.precio).toFixed(2)}</div>
                                <div class="product-stock">Stock: ${producto.stock}</div>
                            </div>
                            <button class="btn btn-primary" onclick="agregarAlCarrito(${producto.id})" ${producto.stock <= 0 ? 'disabled' : ''}>
                                ${producto.stock > 0 ? 'Agregar' : 'Sin stock'}
                            </button>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });
        }
        
        function agregarAlCarrito(id) {
            const producto = productos.find(p => p.id === id);
            if (!producto) return;
            
            const itemExistente = carrito.find(item => item.id === id);
            
            if (itemExistente) {
                if (itemExistente.cantidad < producto.stock) {
                    itemExistente.cantidad++;
                } else {
                    alert('No hay suficiente stock');
                    return;
                }
            } else {
                carrito.push({
                    id: producto.id,
                    nombre: producto.nombre,
                    precio: parseFloat(producto.precio),
                    cantidad: 1,
                    stock: producto.stock
                });
            }
            
            guardarCarrito();
            actualizarContadorCarrito();
            
            // Animación feedback
            const btn = event.target;
            btn.textContent = '✓ Agregado';
            setTimeout(() => {
                btn.textContent = 'Agregar';
            }, 1000);
        }
        
        function abrirCarrito() {
            mostrarCarrito();
            document.getElementById('modalCarrito').classList.add('active');
        }
        
        function cerrarCarrito() {
            document.getElementById('modalCarrito').classList.remove('active');
        }
        
        function mostrarCarrito() {
            const contenido = document.getElementById('carritoContenido');
            
            if (carrito.length === 0) {
                contenido.innerHTML = '<div class="empty-cart">Tu carrito está vacío</div>';
                document.getElementById('btnCheckout').style.display = 'none';
                return;
            }
            
            document.getElementById('btnCheckout').style.display = 'block';
            
            let html = '';
            let total = 0;
            
            carrito.forEach(item => {
                const subtotal = item.precio * item.cantidad;
                total += subtotal;
                
                html += `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name">${item.nombre}</div>
                            <div class="cart-item-price">$${item.precio.toFixed(2)} c/u</div>
                        </div>
                        <div class="cart-item-quantity">
                            <button class="quantity-btn" onclick="cambiarCantidad(${item.id}, -1)">-</button>
                            <span>${item.cantidad}</span>
                            <button class="quantity-btn" onclick="cambiarCantidad(${item.id}, 1)">+</button>
                            <button class="quantity-btn" onclick="eliminarDelCarrito(${item.id})" style="background: #dc3545;">✕</button>
                        </div>
                    </div>
                `;
            });
            
            html += `
                <div class="cart-total">
                    <h4>Total: $${total.toFixed(2)}</h4>
                </div>
            `;
            
            contenido.innerHTML = html;
        }
        
        function cambiarCantidad(id, cambio) {
            const item = carrito.find(i => i.id === id);
            if (!item) return;
            
            item.cantidad += cambio;
            
            if (item.cantidad <= 0) {
                eliminarDelCarrito(id);
            } else if (item.cantidad > item.stock) {
                alert('No hay suficiente stock');
                item.cantidad = item.stock;
            }
            
            guardarCarrito();
            actualizarContadorCarrito();
            mostrarCarrito();
        }
        
        function eliminarDelCarrito(id) {
            carrito = carrito.filter(item => item.id !== id);
            guardarCarrito();
            actualizarContadorCarrito();
            mostrarCarrito();
        }
        
        function actualizarContadorCarrito() {
            const total = carrito.reduce((sum, item) => sum + item.cantidad, 0);
            document.getElementById('cartCount').textContent = total;
        }
        
        function guardarCarrito() {
            localStorage.setItem('carrito', JSON.stringify(carrito));
        }
        
        function mostrarCheckout() {
            if (carrito.length === 0) {
                alert('Tu carrito está vacío');
                return;
            }
            
            const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
            document.getElementById('totalCheckout').textContent = total.toFixed(2);
            
            cerrarCarrito();
            document.getElementById('modalCheckout').classList.add('active');
        }
        
        function cerrarCheckout() {
            document.getElementById('modalCheckout').classList.remove('active');
            document.getElementById('mensajeExito').style.display = 'none';
        }
        
        document.getElementById('formCheckout').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nombre = document.getElementById('clienteNombre').value;
            const email = document.getElementById('clienteEmail').value;
            const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
            
            const pedido = {
                cliente_nombre: nombre,
                cliente_email: email,
                total: total,
                items: carrito.map(item => ({
                    producto_id: item.id,
                    cantidad: item.cantidad,
                    precio: item.precio
                }))
            };
            
            fetch(API_PEDIDOS, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(pedido)
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('mensajeExito').style.display = 'block';
                document.getElementById('formCheckout').style.display = 'none';
                
                // Limpiar carrito
                carrito = [];
                guardarCarrito();
                actualizarContadorCarrito();
                
                setTimeout(() => {
                    cerrarCheckout();
                    document.getElementById('formCheckout').style.display = 'block';
                    document.getElementById('formCheckout').reset();
                }, 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar el pedido');
            });
        });
        
        // Cerrar modales al hacer clic fuera
        document.getElementById('modalCarrito').addEventListener('click', function(e) {
            if (e.target === this) cerrarCarrito();
        });
        
        document.getElementById('modalCheckout').addEventListener('click', function(e) {
            if (e.target === this) cerrarCheckout();
        });
    </script>
</body>
</html>
