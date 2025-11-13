-- Eliminar la base de datos si existe
DROP DATABASE IF EXISTS farmapp;

-- Crear la base de datos
CREATE DATABASE farmapp CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Usar la base de datos
USE farmapp;

-- Tabla de roles
CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL,
    descripcion_rol VARCHAR(255)
) ENGINE=InnoDB;

-- Tabla de géneros
CREATE TABLE generos (
    id_genero INT AUTO_INCREMENT PRIMARY KEY,
    nombre_genero VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id_usuario VARCHAR(20) PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL,
    email_usuario VARCHAR(100) NOT NULL UNIQUE,
    clave_usuario VARCHAR(255) NOT NULL,
    telefono_usuario VARCHAR(20) NOT NULL,
    direccion_usuario VARCHAR(255) NOT NULL,
    id_rol_usuario INT NOT NULL,
    id_genero_usuario INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_rol_usuario) REFERENCES roles(id_rol),
    FOREIGN KEY (id_genero_usuario) REFERENCES generos(id_genero)
) ENGINE=InnoDB;

-- Tabla de categorías
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL,
    descripcion_categoria TEXT
) ENGINE=InnoDB;

-- Tabla de medicamentos
CREATE TABLE medicamentos (
    id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
    nombre_medicamento VARCHAR(100) NOT NULL,
    descripcion_medicamento TEXT,
    precio_medicamento DECIMAL(10, 2) NOT NULL,
    precio_hora DECIMAL(10, 2) NOT NULL,
    precio_dia DECIMAL(10, 2) NOT NULL,
    id_categoria_medicamento INT NOT NULL,
    stock INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_categoria_medicamento) REFERENCES categorias(id_categoria)
) ENGINE=InnoDB;

-- Tabla de imágenes
CREATE TABLE imagenes (
    id_imagen INT AUTO_INCREMENT PRIMARY KEY,
    id_medicamento_imagen INT NOT NULL,
    nombre_imagen VARCHAR(255) NOT NULL,
    ruta_imagen VARCHAR(255),
    principal BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_medicamento_imagen) REFERENCES medicamentos(id_medicamento)
) ENGINE=InnoDB;

-- Tabla de pedidos
CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario VARCHAR(20) NOT NULL,
    total_pedido DECIMAL(10, 2) NOT NULL,
    direccion_entrega VARCHAR(255) NOT NULL,
    telefono_contacto VARCHAR(20) NOT NULL,
    notas_adicionales TEXT,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado_pedido VARCHAR(50) DEFAULT 'Pendiente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB;

-- Tabla de detalle de pedidos
CREATE TABLE detalle_pedidos (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_medicamento INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
    FOREIGN KEY (id_medicamento) REFERENCES medicamentos(id_medicamento)
) ENGINE=InnoDB;

-- Insertar datos iniciales

-- Roles
INSERT INTO roles (nombre_rol, descripcion_rol) VALUES 
('Administrador', 'Control total del sistema'),
('Cliente', 'Usuario normal del sistema');

-- Géneros
INSERT INTO generos (nombre_genero) VALUES 
('Masculino'),
('Femenino'),
('Otro');

-- Categorías
INSERT INTO categorias (nombre_categoria, descripcion_categoria) VALUES 
('Analgésicos', 'Medicamentos para aliviar el dolor'),
('Antibióticos', 'Medicamentos para combatir infecciones bacterianas'),
('Antiinflamatorios', 'Medicamentos para reducir la inflamación'),
('Hidratación', 'Sueros de rehidratación oral'),
('Vitaminas', 'Suplementos vitamínicos');

-- Medicamentos de ejemplo
INSERT INTO medicamentos (nombre_medicamento, descripcion_medicamento, precio_medicamento, precio_hora, precio_dia, id_categoria_medicamento, stock) VALUES 
('Acetaminofen', 'Analgésico y antipirético', 5000, 500, 2000, 1, 100),
('Advilmax', 'Antigripal', 7000, 700, 3000, 3, 80),
('Dolexforte', 'Antibiótico de amplio espectro', 12000, 1200, 5000, 2, 50),
('Electrolit', 'Suero de rehidratación', 12000, 1200, 5000, 2, 50),
('Shot B', 'Suplemento vitamínico', 8000, 800, 3500, 4, 120);

-- Imágenes de ejemplo
INSERT INTO imagenes (id_medicamento_imagen, nombre_imagen, ruta_imagen, principal) VALUES 
(1, 'acetaminofen.png', 'assets/images/acetaminofen.png', TRUE),
(2, 'advilmax.png', 'assets/images/advilmax.png', TRUE),
(3, 'dolexfote.png', 'assets/images/dolexfote.png', TRUE),
(4, 'electrolit.png', 'assets/images/dolexfote.png', TRUE),
(5, 'shotb.png', 'assets/images/shotb.png', TRUE);

-- Usuario administrador de ejemplo (clave: admin123)
INSERT INTO usuarios (id_usuario, nombre_usuario, email_usuario, clave_usuario, telefono_usuario, direccion_usuario, id_rol_usuario, id_genero_usuario) VALUES 
('1234567890', 'Administrador', 'admin@farmapp.com', '1234', '3001234567', 'Calle Principal #123', 1, 1);

-- Usuario cliente de ejemplo (clave: cliente123)
INSERT INTO usuarios (id_usuario, nombre_usuario, email_usuario, clave_usuario, telefono_usuario, direccion_usuario, id_rol_usuario, id_genero_usuario) VALUES 
('0987654321', 'Cliente Ejemplo', 'cliente@ejemplo.com', '1234', '3109876543', 'Avenida Siempreviva 742', 2, 2);


-- Agregar columnas faltantes a la tabla pedidos
ALTER TABLE pedidos 
ADD COLUMN total_pedido DECIMAL(10,2) NOT NULL AFTER id_usuario_pedido,
ADD COLUMN direccion_entrega TEXT NOT NULL AFTER total_pedido,
ADD COLUMN telefono_contacto VARCHAR(15) NOT NULL AFTER direccion_entrega,
ADD COLUMN notas_adicionales TEXT AFTER telefono_contacto,
ADD COLUMN estado_pedido ENUM('pendiente', 'confirmado', 'en camino', 'entregado', 'cancelado') DEFAULT 'pendiente' AFTER notas_adicionales;

-- Agregar columna faltante a detalle_pedido
ALTER TABLE detalle_pedido 
ADD COLUMN precio_detalle DECIMAL(10,2) NOT NULL AFTER cantidad;