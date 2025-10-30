-- Base de datos para el sistema
CREATE DATABASE IF NOT EXISTS tienda_db;
USE tienda_db;

-- Tabla de usuarios administradores
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    categoria_id INT,
    imagen VARCHAR(255),
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

-- Tabla de categorías
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_nombre VARCHAR(100),
    cliente_email VARCHAR(100),
    total DECIMAL(10, 2),
    estado VARCHAR(20) DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de detalles de pedidos
CREATE TABLE pedido_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    producto_id INT,
    cantidad INT,
    precio DECIMAL(10, 2),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Insertar usuario administrador por defecto (password: admin123)
INSERT INTO usuarios (username, password, email) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@tienda.com');

-- Insertar categorías de ejemplo
INSERT INTO categorias (nombre, descripcion, activo) VALUES
('Computadoras', 'Laptops, desktops y accesorios', 1),
('Periféricos', 'Mouse, teclados y más', 1),
('Monitores', 'Pantallas y displays', 1),
('Audio', 'Auriculares y bocinas', 1),
('Accesorios', 'Cables, fundas y más', 1);

-- Insertar algunos productos de ejemplo
INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, imagen) VALUES
('Laptop HP', 'Laptop HP 15.6" Intel Core i5', 599.99, 10, 1, 'laptop.jpg'),
('Mouse Logitech', 'Mouse inalámbrico Logitech M185', 19.99, 50, 2, 'mouse.jpg'),
('Teclado Mecánico', 'Teclado mecánico RGB', 89.99, 25, 2, 'teclado.jpg'),
('Monitor Samsung', 'Monitor Samsung 24" Full HD', 199.99, 15, 3, 'monitor.jpg'),
('Auriculares Sony', 'Auriculares Sony con cancelación de ruido', 149.99, 30, 4, 'auriculares.jpg');
