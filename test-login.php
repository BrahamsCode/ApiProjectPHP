<?php
require_once 'config.php';

echo "<!DOCTYPE html><html><head><title>Test Login</title></head><body>";
echo "<h1>🔍 Diagnóstico de Login</h1>";

// Probar conexión
try {
    $conn = getConnection();
    echo "<p style='color:green;'>✅ Conexión a BD exitosa</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
    exit;
}

// Datos de prueba
$username = 'admin';
$password = 'admin123';

echo "<h2>Datos de prueba:</h2>";
echo "Username: <strong>$username</strong><br>";
echo "Password: <strong>$password</strong><br><br>";

// Buscar usuario
$stmt = $conn->prepare("SELECT id, username, password, email FROM usuarios WHERE username = ?");
$stmt->execute([$username]);
$usuario = $stmt->fetch();

echo "<h2>Resultado en la base de datos:</h2>";
if ($usuario) {
    echo "<p style='color:green;'>✅ Usuario encontrado</p>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Campo</th><th>Valor</th></tr>";
    echo "<tr><td>ID</td><td>" . $usuario['id'] . "</td></tr>";
    echo "<tr><td>Username</td><td>" . $usuario['username'] . "</td></tr>";
    echo "<tr><td>Email</td><td>" . $usuario['email'] . "</td></tr>";
    echo "<tr><td>Password Hash</td><td><code>" . $usuario['password'] . "</code></td></tr>";
    echo "</table><br>";
    
    echo "<h2>Prueba de password_verify():</h2>";
    $verifica = password_verify($password, $usuario['password']);
    
    if ($verifica) {
        echo "<p style='color:green; font-size:20px;'><strong>✅ ¡CONTRASEÑA CORRECTA!</strong></p>";
        echo "<p>El login debería funcionar correctamente.</p>";
    } else {
        echo "<p style='color:red; font-size:20px;'><strong>❌ CONTRASEÑA INCORRECTA</strong></p>";
        echo "<p>El hash en la base de datos NO coincide con la contraseña 'admin123'.</p>";
        
        // Generar nuevo hash
        echo "<h3>🔧 Solución:</h3>";
        $nuevo_hash = password_hash($password, PASSWORD_DEFAULT);
        echo "<p>Ejecuta este comando SQL en MySQL Workbench:</p>";
        echo "<textarea style='width:100%; height:100px; font-family:monospace;'>";
        echo "UPDATE usuarios SET password = '$nuevo_hash' WHERE username = 'admin';";
        echo "</textarea>";
        echo "<p>Después de ejecutar ese SQL, el login debería funcionar.</p>";
    }
    
} else {
    echo "<p style='color:red;'>❌ <strong>Usuario NO encontrado</strong></p>";
    echo "<h3>🔧 Solución:</h3>";
    echo "<p>Ejecuta este comando SQL en MySQL Workbench:</p>";
    $nuevo_hash = password_hash($password, PASSWORD_DEFAULT);
    echo "<textarea style='width:100%; height:100px; font-family:monospace;'>";
    echo "INSERT INTO usuarios (username, password, email) VALUES ('admin', '$nuevo_hash', 'admin@tienda.com');";
    echo "</textarea>";
}

echo "</body></html>";
?>
```

---

## **PASOS FINALES:**

### 1️⃣ Guarda el archivo `api/login.php` sin las líneas de debug

### 2️⃣ Crea el archivo `test-login.php` en la raíz con el código de arriba

### 3️⃣ Abre en el navegador:
```
http://localhost/tienda/test-login.php
```

### 4️⃣ Lee el resultado:
- Si dice **"✅ ¡CONTRASEÑA CORRECTA!"** → El login ya debería funcionar
- Si dice **"❌ CONTRASEÑA INCORRECTA"** → Copia el SQL que te muestra y ejecútalo en MySQL Workbench

### 5️⃣ Una vez arreglado, prueba el login de nuevo en:
```
http://localhost/tienda/demo-api.html