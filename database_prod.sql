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
    id_medicamento VARCHAR(10) PRIMARY KEY,
    nombre_medicamento VARCHAR(100) NOT NULL,
    descripcion_medicamento TEXT,
    precio_medicamento DECIMAL(10, 2) NOT NULL,
    id_categoria_medicamento INT NOT NULL,
    stock INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_categoria_medicamento) REFERENCES categorias(id_categoria)
) ENGINE=InnoDB;

-- Tabla de imágenes
CREATE TABLE imagenes (
    id_imagen INT AUTO_INCREMENT PRIMARY KEY,
    id_medicamento_imagen VARCHAR(10) NOT NULL,
    nombre_imagen VARCHAR(255) NOT NULL,
    ruta_imagen VARCHAR(255),
    FOREIGN KEY (id_medicamento_imagen) REFERENCES medicamentos(id_medicamento)
) ENGINE=InnoDB;

-- Tabla de pedidos
CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario_pedido VARCHAR(20) NOT NULL,
    total_pedido DECIMAL(10,2) NOT NULL,
    direccion_entrega TEXT NOT NULL,
    telefono_contacto VARCHAR(15) NOT NULL,
    notas_adicionales TEXT,
    estado_pedido ENUM('pendiente', 'confirmado', 'en camino', 'entregado', 'cancelado') DEFAULT 'pendiente',
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario_pedido) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB;

-- Tabla de detalle de pedidos
CREATE TABLE detalle_pedidos (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_medicamento VARCHAR(10) NOT NULL,
    cantidad INT NOT NULL,
    precio_detalle DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
    FOREIGN KEY (id_medicamento) REFERENCES medicamentos(id_medicamento)
) ENGINE=InnoDB;

-- Insertar datos iniciales
INSERT INTO roles (nombre_rol, descripcion_rol) VALUES 
('Administrador', 'Control total del sistema'),
('Cliente', 'Usuario normal del sistema');

INSERT INTO generos (nombre_genero) VALUES 
('Masculino'),
('Femenino'),
('Otro');

INSERT INTO categorias (nombre_categoria, descripcion_categoria) VALUES 
('Analgésicos', 'Medicamentos para aliviar el dolor'),
('Antibióticos', 'Medicamentos para combatir infecciones bacterianas'),
('Antiinflamatorios', 'Medicamentos para reducir la inflamación'),
('Antigripales', 'Medicamentos para síntomas gripales'),
('Vitaminas', 'Suplementos vitamínicos');

INSERT INTO usuarios (id_usuario, nombre_usuario, email_usuario, clave_usuario, telefono_usuario, direccion_usuario, id_rol_usuario, id_genero_usuario) VALUES 
('1234567890', 'Administrador', 'admin@farmapp.com', '1234', '3001234567', 'Calle Principal #123', 1, 1);

INSERT INTO medicamentos (id_medicamento, nombre_medicamento, descripcion_medicamento, precio_medicamento, id_categoria_medicamento, stock) VALUES 
('M001', 'Acetaminofen 500mg', 'Alivio del dolor y fiebre', 2500.00, 1, 100),
('T002', 'Dolex Forte 500mg', 'Antibiótico de amplio espectro', 4800.00, 2, 50),
('T004', 'Advilmax', 'Alivio de síntomas gripales', 5200.00, 4, 80),
('M005', 'Shot B', 'Suplemento multivitamínico', 43500.00, 5, 30);