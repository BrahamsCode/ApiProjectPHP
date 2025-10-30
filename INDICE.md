# 📁 ÍNDICE DEL PROYECTO - Sistema de Tienda con API

## 🎯 EMPEZAR AQUÍ

Si es tu primera vez con este proyecto, lee en este orden:

1. **INICIO-RAPIDO.md** - Instala el sistema en 5 minutos ⚡
2. **ARQUITECTURA.md** - Entiende cómo funciona todo 🏗️
3. **API-DOCS.md** - Referencia completa de las APIs 📡
4. **SEGURIDAD.md** - Antes de producción 🔒

---

## 📂 ESTRUCTURA DE ARCHIVOS

```
proyecto/
│
├── 📄 INICIO-RAPIDO.md          ⚡ Guía de instalación rápida (5 min)
├── 📄 README.md                 📖 Documentación general completa
├── 📄 API-DOCS.md               📡 Documentación de todas las APIs
├── 📄 ARQUITECTURA.md           🏗️ Explicación del sistema
├── 📄 SEGURIDAD.md              🔒 Guía de seguridad y mejores prácticas
├── 📄 INDICE.md                 📋 Este archivo
│
├── 📄 database.sql              💾 Script de base de datos
├── 📄 config.php                ⚙️ Configuración y funciones comunes
├── 📄 .htaccess                 🔧 Configuración Apache
│
├── 📄 index.php                 🛍️ TIENDA (Frontend público)
├── 📄 demo-api.html             🧪 Demo interactiva de APIs
├── 📄 ejemplos-api.html         💻 Ejemplos de código de APIs
│
├── 📁 api/                      🔌 APIs REST
│   ├── login.php                   🔐 API de autenticación
│   ├── categorias.php              📂 API de categorías (CRUD)
│   ├── productos.php               📦 API de productos (CRUD)
│   └── pedidos.php                 📋 API de pedidos (CRUD)
│
└── 📁 admin/                    👨‍💼 PANEL ADMINISTRATIVO
    ├── login.php                   🔑 Página de login
    ├── dashboard.php               📊 Dashboard principal
    ├── productos.php               📦 Gestión de productos
    ├── categorias.php              📂 Gestión de categorías
    ├── pedidos.php                 📋 Gestión de pedidos
    └── logout.php                  🚪 Cerrar sesión
```

---

## 📚 GUÍA DE DOCUMENTACIÓN

### 🚀 INICIO-RAPIDO.md
**Lee esto primero si quieres instalarlo rápido**
- Instalación paso a paso
- Credenciales de acceso
- Solución de problemas comunes
- URLs importantes

### 📖 README.md
**Documentación general completa**
- Características del sistema
- Instalación detallada
- Estructura de archivos
- Ejemplos de uso
- Personalización
- FAQ

### 📡 API-DOCS.md
**Referencia completa de todas las APIs**
- API de Login
- API de Categorías
- API de Productos
- API de Pedidos
- Códigos de estado HTTP
- Ejemplos de uso con cURL y JavaScript

### 🏗️ ARQUITECTURA.md
**Cómo funciona el sistema internamente**
- Diagrama de arquitectura
- Flujo de datos
- Explicación de cada API
- Ventajas del diseño
- Cómo agregar nuevas funcionalidades
- Debugging

### 🔒 SEGURIDAD.md
**Guía de seguridad para producción**
- Cambiar credenciales
- Configuración segura
- Prevención de ataques
- Backup y recuperación
- Checklist pre-producción

---

## 🎯 CASOS DE USO - ¿QUÉ NECESITAS?

### "Quiero instalar el sistema rápido"
→ Lee: **INICIO-RAPIDO.md**

### "Quiero entender cómo funciona"
→ Lee: **ARQUITECTURA.md**

### "Necesito consumir la API desde mi app"
→ Lee: **API-DOCS.md**

### "Quiero personalizar el diseño"
→ Lee: **README.md** sección "Personalización"

### "Voy a poner esto en producción"
→ Lee: **SEGURIDAD.md** (muy importante)

### "Tengo un error y no sé qué hacer"
→ Lee: **INICIO-RAPIDO.md** sección "Solución de Problemas"

### "Quiero probar la API sin escribir código"
→ Abre: **demo-api.html** en tu navegador

### "Necesito ejemplos de código"
→ Abre: **ejemplos-api.html** o lee **API-DOCS.md**

---

## 🔗 URLS IMPORTANTES

Una vez instalado, estas son las URLs principales:

```
🛍️ TIENDA (Frontend público)
http://localhost/tienda/

👨‍💼 PANEL ADMIN
http://localhost/tienda/admin/login.php

🧪 DEMO DE API
http://localhost/tienda/demo-api.html

💻 EJEMPLOS DE API
http://localhost/tienda/ejemplos-api.html

📡 APIs REST
http://localhost/tienda/api/login.php
http://localhost/tienda/api/categorias.php
http://localhost/tienda/api/productos.php
http://localhost/tienda/api/pedidos.php
```

---

## 🔐 CREDENCIALES POR DEFECTO

```
Usuario: admin
Contraseña: admin123
```

⚠️ **IMPORTANTE:** Cambia estas credenciales antes de usar en producción.

---

## 🎨 ARCHIVOS PARA MODIFICAR

### Quiero cambiar los colores
**Archivos a editar:**
- `index.php` - Busca `background: linear-gradient`
- `admin/*.php` - Busca `.navbar` o `linear-gradient`

### Quiero cambiar el logo o nombre
**Archivos a editar:**
- `index.php` - Línea con `<h1>🛍️ Mi Tienda Online</h1>`
- `admin/*.php` - Línea con `<h1>🛍️ Panel Administrativo</h1>`

### Quiero agregar un nuevo campo a productos
**Pasos:**
1. Edita `database.sql` - Agrega columna a tabla productos
2. Edita `api/productos.php` - Agrega el campo en INSERT/UPDATE
3. Edita `admin/productos.php` - Agrega input en el formulario
4. Edita `index.php` - Muestra el campo si es necesario

### Quiero cambiar la conexión a la BD
**Archivo a editar:**
- `config.php` - Líneas 3-6

---

## 🧪 PROBAR EL SISTEMA

### Opción 1: Usar la interfaz web
1. Abre `http://localhost/tienda/`
2. Agrega productos al carrito
3. Haz un pedido
4. Ve a `http://localhost/tienda/admin/login.php`
5. Login con admin/admin123
6. Revisa el pedido en "Pedidos"

### Opción 2: Usar la demo interactiva
1. Abre `demo-api.html` en el navegador
2. Prueba cada botón
3. Ve las respuestas JSON

### Opción 3: Usar cURL
```bash
# Login
curl -X POST http://localhost/tienda/api/login.php \
-H "Content-Type: application/json" \
-d '{"username":"admin","password":"admin123"}'

# Obtener productos
curl http://localhost/tienda/api/productos.php
```

---

## 🆘 AYUDA RÁPIDA

### Error: No se puede conectar a la base de datos
1. Verifica que MySQL esté ejecutándose
2. Revisa config.php (líneas 3-6)
3. Asegúrate de haber importado database.sql

### Error: 404 Not Found
1. Verifica que Apache esté ejecutándose
2. Confirma la ruta del proyecto
3. Revisa la configuración de virtual hosts

### La API no responde
1. Verifica que el archivo existe en /api/
2. Revisa los permisos de archivos (755 para directorios, 644 para archivos)
3. Mira el log de errores de Apache

### Los estilos no cargan
1. Abre DevTools (F12) y ve a Console
2. Verifica errores de rutas
3. Confirma que los archivos CSS inline estén correctos

---

## 🚀 ROADMAP SUGERIDO

### Fase 1: Instalación ✅
- [x] Instalar sistema
- [x] Importar base de datos
- [x] Probar login
- [x] Crear primer producto

### Fase 2: Personalización
- [ ] Cambiar colores
- [ ] Cambiar nombre/logo
- [ ] Agregar productos reales
- [ ] Crear categorías

### Fase 3: Mejoras
- [ ] Subir imágenes reales
- [ ] Implementar JWT
- [ ] Agregar paginación
- [ ] Agregar búsqueda

### Fase 4: Producción
- [ ] Leer SEGURIDAD.md completo
- [ ] Cambiar credenciales
- [ ] Configurar HTTPS
- [ ] Setup backups automáticos
- [ ] Testing de seguridad

---

## 📞 RECURSOS ADICIONALES

### Aprender más sobre PHP
- https://www.php.net/manual/es/
- https://www.w3schools.com/php/

### Aprender más sobre APIs REST
- https://restfulapi.net/
- https://developer.mozilla.org/es/docs/Web/HTTP

### Aprender más sobre MySQL
- https://dev.mysql.com/doc/
- https://www.mysqltutorial.org/

### Herramientas útiles
- Postman (testing APIs): https://www.postman.com/
- phpMyAdmin (gestión BD): https://www.phpmyadmin.net/
- VS Code (editor): https://code.visualstudio.com/

---

## ✨ CARACTERÍSTICAS DESTACADAS

### ✅ Completamente basado en API
- Todo el acceso a datos pasa por API REST
- Fácil integrar con apps móviles
- Admin y frontend usan las mismas APIs

### ✅ Sin frameworks
- PHP puro y vanilla JavaScript
- Fácil de entender y modificar
- Sin dependencias externas

### ✅ Seguro por diseño
- Prepared statements (anti SQL injection)
- Passwords hasheados
- Validación de datos
- CORS configurado

### ✅ Responsive
- Funciona en móvil, tablet y desktop
- Diseño moderno con gradientes
- UX intuitiva

### ✅ Completo
- CRUD de productos
- CRUD de categorías
- Gestión de pedidos
- Sistema de carrito
- Dashboard con estadísticas

---

## 🎉 ¡LISTO PARA EMPEZAR!

Este sistema es una base sólida para construir tu tienda online. Lee los documentos en orden, prueba las APIs, y personaliza según tus necesidades.

**¿Dudas?** Revisa la documentación completa en cada archivo .md

**¿Problemas?** Lee INICIO-RAPIDO.md sección "Solución de Problemas"

**¿Ideas?** Revisa ARQUITECTURA.md sección "Cómo agregar nuevas funcionalidades"

---

**¡Feliz desarrollo! 🚀**
