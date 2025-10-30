# 🔒 SEGURIDAD Y MEJORES PRÁCTICAS

## ⚠️ IMPORTANTE: Antes de Poner en Producción

Este proyecto está diseñado para desarrollo y aprendizaje. Antes de usarlo en producción, implementa las siguientes medidas de seguridad:

---

## 🛡️ 1. Base de Datos

### ✅ Cambiar Credenciales
```php
// config.php - NUNCA uses estas credenciales en producción
define('DB_USER', 'tu_usuario_seguro');  // NO usar 'root'
define('DB_PASS', 'tu_contraseña_fuerte');  // Mínimo 12 caracteres
```

### ✅ Privilegios Mínimos
Crea un usuario MySQL específico con privilegios limitados:

```sql
-- Crear usuario específico para la aplicación
CREATE USER 'tienda_user'@'localhost' IDENTIFIED BY 'contraseña_segura_aqui';

-- Dar solo los permisos necesarios
GRANT SELECT, INSERT, UPDATE, DELETE ON tienda_db.* TO 'tienda_user'@'localhost';

-- Aplicar cambios
FLUSH PRIVILEGES;
```

### ✅ Backup Regular
```bash
# Backup diario automático
mysqldump -u tienda_user -p tienda_db > backup_$(date +%Y%m%d).sql
```

---

## 🔐 2. Autenticación y Sesiones

### ✅ Cambiar Contraseña Admin
```php
// Generar nueva contraseña
$nueva_contraseña = password_hash('tu_contraseña_super_segura', PASSWORD_DEFAULT);

// Actualizar en MySQL
UPDATE usuarios SET password = '$nueva_contraseña' WHERE username = 'admin';
```

### ✅ Configuración Segura de Sesiones
Agrega al inicio de `config.php`:

```php
// Configuración segura de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);  // Solo si usas HTTPS
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
session_regenerate_id(true);
```

### ✅ Implementar Rate Limiting
Para prevenir ataques de fuerza bruta en el login:

```php
// En login.php, agregar contador de intentos
if (!isset($_SESSION['intentos_login'])) {
    $_SESSION['intentos_login'] = 0;
    $_SESSION['ultimo_intento'] = time();
}

// Limitar a 5 intentos por 15 minutos
if ($_SESSION['intentos_login'] >= 5) {
    $tiempo_transcurrido = time() - $_SESSION['ultimo_intento'];
    if ($tiempo_transcurrido < 900) { // 15 minutos
        $error = 'Demasiados intentos. Espera ' . (15 - floor($tiempo_transcurrido/60)) . ' minutos';
        exit();
    } else {
        $_SESSION['intentos_login'] = 0;
    }
}
```

---

## 🌐 3. HTTPS y Seguridad de Transporte

### ✅ Forzar HTTPS
En `.htaccess` (descomenta estas líneas):

```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### ✅ Headers de Seguridad
Agrega en `config.php`:

```php
// Headers de seguridad
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Content Security Policy
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");
```

---

## 🔍 4. Validación y Sanitización

### ✅ Validar Entrada del Usuario
```php
// Ejemplo de validación robusta
function validarProducto($data) {
    $errores = [];
    
    // Validar nombre
    if (empty($data['nombre']) || strlen($data['nombre']) < 3) {
        $errores[] = "Nombre debe tener al menos 3 caracteres";
    }
    
    // Validar precio
    if (!is_numeric($data['precio']) || $data['precio'] < 0) {
        $errores[] = "Precio debe ser un número positivo";
    }
    
    // Validar stock
    if (!is_numeric($data['stock']) || $data['stock'] < 0) {
        $errores[] = "Stock debe ser un número positivo";
    }
    
    return $errores;
}
```

### ✅ Sanitizar Output
```php
// Siempre usar htmlspecialchars para mostrar datos
echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8');
```

### ✅ Validar Tipos de Archivo (si implementas uploads)
```php
function validarImagen($archivo) {
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    $tamaño_maximo = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($archivo['type'], $tipos_permitidos)) {
        return "Tipo de archivo no permitido";
    }
    
    if ($archivo['size'] > $tamaño_maximo) {
        return "Archivo muy grande";
    }
    
    return null;
}
```

---

## 🚫 5. Protección contra Ataques

### ✅ CSRF Protection
Implementar tokens CSRF:

```php
// Generar token
function generarTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validar token
function validarTokenCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// En formularios
<input type="hidden" name="csrf_token" value="<?php echo generarTokenCSRF(); ?>">
```

### ✅ SQL Injection Prevention
Ya está implementado con PDO y prepared statements, pero siempre:

```php
// ✅ CORRECTO - Usar prepared statements
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);

// ❌ INCORRECTO - Nunca hacer esto
$query = "SELECT * FROM productos WHERE id = $id";
```

### ✅ XSS Prevention
```php
// Escapar HTML en outputs
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Uso
echo e($producto['nombre']);
```

---

## 📝 6. Logging y Monitoreo

### ✅ Implementar Logging
```php
// logger.php
function logAccion($tipo, $mensaje, $usuario_id = null) {
    $log_file = __DIR__ . '/logs/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $log = "[{$timestamp}] [{$tipo}] IP:{$ip} Usuario:{$usuario_id} - {$mensaje}\n";
    
    file_put_contents($log_file, $log, FILE_APPEND);
}

// Uso
logAccion('LOGIN', 'Inicio de sesión exitoso', $usuario_id);
logAccion('ERROR', 'Intento de acceso no autorizado');
```

### ✅ Crear directorio de logs
```bash
mkdir logs
chmod 750 logs
echo "deny from all" > logs/.htaccess
```

---

## 🔧 7. Configuración de PHP

### ✅ php.ini - Configuración Segura
```ini
; Ocultar versión de PHP
expose_php = Off

; Límites de recursos
max_execution_time = 30
max_input_time = 60
memory_limit = 128M
post_max_size = 20M
upload_max_filesize = 20M

; Errores - NO mostrar en producción
display_errors = Off
log_errors = On
error_reporting = E_ALL
error_log = /var/log/php_errors.log

; Sesiones
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1
```

---

## 📦 8. Gestión de Dependencias

### ✅ Actualizar PHP y MySQL
```bash
# Ubuntu/Debian
sudo apt update
sudo apt upgrade php mysql-server

# Verificar versiones
php -v
mysql --version
```

### ✅ Mantener Actualizado
- Suscribirse a security advisories de PHP
- Revisar CVEs de MySQL
- Actualizar código regularmente

---

## 🔒 9. Backup y Recuperación

### ✅ Script de Backup Automático
```bash
#!/bin/bash
# backup.sh

# Variables
FECHA=$(date +%Y%m%d_%H%M%S)
DB_USER="tienda_user"
DB_PASS="tu_contraseña"
DB_NAME="tienda_db"
BACKUP_DIR="/ruta/backups"

# Backup de base de datos
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_$FECHA.sql

# Backup de archivos
tar -czf $BACKUP_DIR/files_$FECHA.tar.gz /ruta/proyecto

# Eliminar backups antiguos (más de 30 días)
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
```

### ✅ Automatizar con cron
```bash
# crontab -e
0 2 * * * /ruta/backup.sh
```

---

## 🧪 10. Testing de Seguridad

### ✅ Herramientas Recomendadas
```bash
# OWASP ZAP - Escáner de vulnerabilidades
# SQLMap - Testing de SQL injection
# Nikto - Web server scanner

# Ejemplo con nikto
nikto -h http://tudominio.com
```

### ✅ Checklist Manual
- [ ] Probar SQL Injection en todos los inputs
- [ ] Verificar XSS en campos de texto
- [ ] Intentar acceso directo a rutas admin sin login
- [ ] Verificar CSRF en formularios
- [ ] Probar ataques de fuerza bruta en login
- [ ] Verificar que archivos sensibles no sean accesibles

---

## 📋 11. Checklist Pre-Producción

### Antes de lanzar, verifica:

- [ ] Todas las contraseñas cambiadas
- [ ] HTTPS habilitado y forzado
- [ ] display_errors = Off en PHP
- [ ] Usuario MySQL con privilegios limitados
- [ ] Headers de seguridad configurados
- [ ] Backups automáticos configurados
- [ ] Logging implementado
- [ ] Rate limiting en login
- [ ] CSRF tokens en formularios
- [ ] Validación completa de inputs
- [ ] Archivos sensibles protegidos
- [ ] Testing de seguridad realizado
- [ ] Plan de respuesta a incidentes documentado

---

## 🆘 12. Respuesta a Incidentes

### Si detectas una brecha de seguridad:

1. **Contener**: Desconecta el servidor si es necesario
2. **Investigar**: Revisa logs para entender el alcance
3. **Remediar**: Parchea la vulnerabilidad
4. **Comunicar**: Notifica a usuarios si datos fueron comprometidos
5. **Prevenir**: Implementa medidas para evitar repetición

### Contactos de Emergencia
```
# Guardar en lugar seguro
Admin: [Teléfono/Email]
Hosting: [Soporte técnico]
Base de datos: [Backup/Recovery]
```

---

## 📚 Recursos Adicionales

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Guide](https://www.php.net/manual/en/security.php)
- [MySQL Security](https://dev.mysql.com/doc/refman/8.0/en/security.html)
- [Mozilla Observatory](https://observatory.mozilla.org/)
- [Security Headers](https://securityheaders.com/)

---

## ⚡ Mejoras Futuras Recomendadas

1. **Autenticación de dos factores (2FA)**
2. **OAuth para login social**
3. **API Key authentication para la API**
4. **WAF (Web Application Firewall)**
5. **CDN para protección DDoS**
6. **Auditoría de seguridad profesional**
7. **Implementar HSTS**
8. **Subresource Integrity (SRI)**

---

**Recuerda**: La seguridad es un proceso continuo, no un estado final. 
Mantén el sistema actualizado y monitorea constantemente.
