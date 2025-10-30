# Panel Administrativo y Tienda Online en PHP

Sistema completo de gestión de tienda con panel administrativo, API REST y frontend de tienda.

## 🚀 Características

### Panel Administrativo
- ✅ Sistema de autenticación (login/logout)
- 📊 Dashboard con estadísticas
- 📦 Gestión completa de productos (CRUD)
- 📋 Gestión de pedidos
- 🔔 Alertas de bajo stock

### API REST
- 🌐 API para productos (GET, POST, PUT, DELETE)
- 🌐 API para pedidos (GET, POST, PUT)
- 📡 Endpoints RESTful
- 🔒 Headers CORS configurados

### Frontend Tienda
- 🛒 Carrito de compras
- 💳 Sistema de checkout
- 📱 Diseño responsive
- 💾 LocalStorage para persistencia del carrito
- ⚡ Consumo de API en tiempo real

## 📁 Estructura de Archivos

```
proyecto/
├── admin/
│   ├── login.php          # Página de login
│   ├── dashboard.php      # Panel principal
│   ├── productos.php      # Gestión de productos
│   ├── pedidos.php        # Gestión de pedidos
│   └── logout.php         # Cerrar sesión
├── api/
│   ├── productos.php      # API REST de productos
│   └── pedidos.php        # API REST de pedidos
├── config.php             # Configuración y funciones
├── database.sql           # Script de base de datos
└── index.php              # Frontend de la tienda
```

## 🔧 Instalación

### 1. Requisitos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Extensión PDO de PHP

### 2. Configurar Base de Datos

```bash
# Crear base de datos
mysql -u root -p

# Importar el script
mysql -u root -p < database.sql
```

O usa phpMyAdmin para importar el archivo `database.sql`

### 3. Configurar Conexión

Edita el archivo `config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'tienda_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 4. Configurar Servidor

**Para Apache:**
Copia los archivos a la carpeta `htdocs` o `www`

**Para desarrollo local:**
```bash
php -S localhost:8000
```

## 👤 Credenciales de Acceso

### Panel Administrativo
- **URL:** http://localhost/admin/login.php
- **Usuario:** admin
- **Contraseña:** admin123

### Crear nuevos usuarios
Para crear un nuevo usuario administrador, ejecuta en MySQL:

```sql
INSERT INTO usuarios (username, password, email) 
VALUES ('nuevo_usuario', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'email@ejemplo.com');
```

Para generar un nuevo hash de contraseña en PHP:
```php
echo password_hash('tu_contraseña', PASSWORD_DEFAULT);
```

## 📖 Uso de la API

### Productos

**Obtener todos los productos:**
```http
GET /api/productos.php
```

**Obtener un producto:**
```http
GET /api/productos.php?id=1
```

**Crear producto:**
```http
POST /api/productos.php
Content-Type: application/json

{
  "nombre": "Producto Nuevo",
  "descripcion": "Descripción del producto",
  "precio": 99.99,
  "stock": 50,
  "imagen": "imagen.jpg",
  "activo": 1
}
```

**Actualizar producto:**
```http
PUT /api/productos.php?id=1
Content-Type: application/json

{
  "nombre": "Producto Actualizado",
  "descripcion": "Nueva descripción",
  "precio": 89.99,
  "stock": 45,
  "imagen": "imagen.jpg",
  "activo": 1
}
```

**Eliminar producto:**
```http
DELETE /api/productos.php?id=1
```

### Pedidos

**Obtener todos los pedidos:**
```http
GET /api/pedidos.php
```

**Obtener un pedido:**
```http
GET /api/pedidos.php?id=1
```

**Crear pedido:**
```http
POST /api/pedidos.php
Content-Type: application/json

{
  "cliente_nombre": "Juan Pérez",
  "cliente_email": "juan@email.com",
  "total": 299.99,
  "items": [
    {
      "producto_id": 1,
      "cantidad": 2,
      "precio": 99.99
    }
  ]
}
```

**Actualizar estado del pedido:**
```http
PUT /api/pedidos.php?id=1
Content-Type: application/json

{
  "estado": "completado"
}
```

## 🎨 Personalización

### Cambiar colores
Edita las variables CSS en cada archivo:
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

### Agregar nuevos campos
1. Modifica la tabla en `database.sql`
2. Actualiza la API correspondiente
3. Actualiza los formularios en el frontend/admin

## 🔒 Seguridad

- ✅ Contraseñas hasheadas con `password_hash()`
- ✅ Prepared statements para prevenir SQL injection
- ✅ Validación de sesiones
- ✅ Protección de rutas administrativas
- ⚠️ **Importante:** En producción, asegúrate de:
  - Cambiar las credenciales de base de datos
  - Usar HTTPS
  - Configurar permisos adecuados de archivos
  - Habilitar error_reporting en OFF

## 🐛 Solución de Problemas

### Error de conexión a la base de datos
- Verifica las credenciales en `config.php`
- Asegúrate de que MySQL esté ejecutándose
- Verifica que la base de datos exista

### Error 404 en las rutas
- Verifica la configuración del servidor
- Asegúrate de que mod_rewrite esté habilitado (Apache)

### Los estilos no se cargan
- Verifica las rutas relativas en los archivos
- Revisa la consola del navegador para errores

## 📝 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:
1. Fork el proyecto
2. Crea una rama para tu feature
3. Commit tus cambios
4. Push a la rama
5. Abre un Pull Request

## 📧 Contacto

Para preguntas o sugerencias, por favor abre un issue en el repositorio.

---

¡Disfruta de tu nuevo panel administrativo! 🎉