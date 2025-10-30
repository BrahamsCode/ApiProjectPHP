# 🚀 GUÍA RÁPIDA DE INICIO

## 📋 Pasos de Instalación (5 minutos)

### 1️⃣ Importar Base de Datos
```bash
# Opción A: Línea de comandos
mysql -u root -p < database.sql

# Opción B: phpMyAdmin
# - Abre phpMyAdmin
# - Crea base de datos "tienda_db"
# - Importa el archivo database.sql
```

### 2️⃣ Configurar Conexión
Edita `config.php` líneas 3-6:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'tienda_db');
define('DB_USER', 'root');        // Tu usuario MySQL
define('DB_PASS', '');            // Tu contraseña MySQL
```

### 3️⃣ Subir Archivos
Copia todos los archivos a:
- **XAMPP:** C:\xampp\htdocs\tienda\
- **WAMP:** C:\wamp\www\tienda\
- **LAMP:** /var/www/html/tienda/

### 4️⃣ Acceder al Sistema

**Panel Administrativo:**
```
http://localhost/tienda/admin/login.php

Usuario: admin
Contraseña: admin123
```

**Tienda (Frontend):**
```
http://localhost/tienda/
```

---

## 📁 Estructura del Proyecto

```
tienda/
│
├── 📄 index.php                  # Tienda (Frontend público)
├── 📄 config.php                 # Configuración de BD y funciones
├── 📄 database.sql               # Script de base de datos
├── 📄 .htaccess                  # Configuración Apache
├── 📄 README.md                  # Documentación completa
│
├── 📁 admin/                     # PANEL ADMINISTRATIVO
│   ├── login.php                 # 🔐 Página de login
│   ├── dashboard.php             # 📊 Dashboard principal
│   ├── productos.php             # 📦 Gestión de productos
│   ├── pedidos.php               # 📋 Gestión de pedidos
│   └── logout.php                # 🚪 Cerrar sesión
│
└── 📁 api/                       # API REST
    ├── productos.php             # Endpoints de productos
    └── pedidos.php               # Endpoints de pedidos
```

---

## 🎯 Funcionalidades Principales

### 🛍️ TIENDA (Frontend)
✅ Catálogo de productos en tiempo real
✅ Carrito de compras con localStorage
✅ Sistema de checkout
✅ Diseño responsive
✅ Validación de stock en tiempo real

### 👨‍💼 PANEL ADMIN
✅ Login seguro con sesiones
✅ Dashboard con estadísticas
✅ CRUD completo de productos
✅ Gestión de pedidos
✅ Cambio de estados de pedidos
✅ Alertas de stock bajo

### 🔌 API REST
✅ GET, POST, PUT, DELETE para productos
✅ GET, POST, PUT para pedidos
✅ Respuestas en JSON
✅ Headers CORS configurados

---

## 🔑 Endpoints de la API

### Productos
```http
GET    /api/productos.php           # Todos los productos
GET    /api/productos.php?id=1      # Un producto
POST   /api/productos.php           # Crear producto
PUT    /api/productos.php?id=1      # Actualizar producto
DELETE /api/productos.php?id=1      # Eliminar producto
```

### Pedidos
```http
GET    /api/pedidos.php             # Todos los pedidos
GET    /api/pedidos.php?id=1        # Un pedido con items
POST   /api/pedidos.php             # Crear pedido
PUT    /api/pedidos.php?id=1        # Actualizar estado
```

---

## 🧪 Prueba Rápida

### 1. Crear un Producto (API)
```bash
curl -X POST http://localhost/tienda/api/productos.php \
-H "Content-Type: application/json" \
-d '{
  "nombre": "Producto Prueba",
  "descripcion": "Test",
  "precio": 99.99,
  "stock": 100,
  "activo": 1
}'
```

### 2. Ver Productos (API)
```bash
curl http://localhost/tienda/api/productos.php
```

### 3. Probar Frontend
1. Abre: http://localhost/tienda/
2. Agrega productos al carrito
3. Finaliza la compra

### 4. Ver en Admin
1. Login: http://localhost/tienda/admin/login.php
2. Ver el pedido en "Pedidos"
3. Cambiar estado del pedido

---

## 🎨 Personalización Rápida

### Cambiar Colores
Busca en todos los archivos:
```css
/* De: */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* A: */
background: linear-gradient(135deg, #TU_COLOR1 0%, #TU_COLOR2 100%);
```

### Cambiar Título
```php
// En cada archivo HTML/PHP, busca:
<h1>🛍️ Mi Tienda Online</h1>

// Cambia a:
<h1>🛍️ TU NOMBRE</h1>
```

---

## ⚠️ Solución de Problemas

### ❌ Error: "Access denied for user"
**Solución:** Verifica usuario/contraseña en `config.php`

### ❌ Error: "Unknown database 'tienda_db'"
**Solución:** Importa `database.sql` primero

### ❌ Error: "Call to undefined function password_verify"
**Solución:** PHP 5.5+ requerido. Actualiza PHP

### ❌ Estilos no se cargan
**Solución:** Verifica las rutas en navegador (F12 > Console)

### ❌ API no responde
**Solución:** 
1. Verifica que Apache esté corriendo
2. Revisa permisos de archivos
3. Verifica mod_rewrite esté activo

---

## 🚀 Siguientes Pasos

1. ✅ Instala el sistema
2. 📝 Cambia la contraseña de admin
3. 🎨 Personaliza colores y textos
4. 📦 Agrega tus productos
5. 🛒 Prueba la tienda
6. 🔒 En producción: activa HTTPS

---

## 📞 Necesitas Ayuda?

1. Lee el **README.md** completo para más detalles
2. Revisa la consola del navegador (F12)
3. Verifica logs de PHP y MySQL
4. Asegúrate de cumplir los requisitos mínimos

---

## 🎉 ¡Listo!

Tu panel administrativo está listo para usar. 

**URLs importantes:**
- Tienda: http://localhost/tienda/
- Admin: http://localhost/tienda/admin/login.php
- API Productos: http://localhost/tienda/api/productos.php
- API Pedidos: http://localhost/tienda/api/pedidos.php

¡Feliz venta! 🛍️
