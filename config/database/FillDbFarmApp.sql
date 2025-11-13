-- By Gaboh - 20250311
-- Database: `FarmApp`

USE FarmApp;

INSERT INTO roles VALUES
(null, 'Administrador'),
(null, 'Usuario'),
(null, 'Secretaria');

INSERT INTO generos VALUES
(null, 'Hombre'),
(null, 'Mujer'),
(null, 'Otro');

INSERT INTO usuarios VALUES
('761', 'sebatian Lopez', 'sebas@uno.com', '1234', '3173640722', 'Calle 11 Norte', 1, 1),
('341', 'Natalia', 'Natalia@uno.com', '1234', '3107046950', 'Carrera 2 # 10-22', 3, 2),
('762', 'Duver ', 'Duver@uno.com', '1234', '3229407955', 'Carrera 8 # 11-34', 2, 1),

INSERT INTO marcas VALUES
(null, 'Dell'),
(null, 'Acer'),
(null, 'HP'),
(null, 'Lenovo'),
(null, 'Asus'),
(null, 'MSI');

INSERT INTO categorias VALUES
(null, 'portatil'),
(null, 'escritorio'),
(null, 'todo en uno'),
(null, 'gamer'),
(null, 'tablet');

INSERT INTO productos VALUES
('EP001', 'Portatil Acer 302', 'Aqui la descripción', 3200000, 12000, 80000, 2, 1),
('T002', 'Lenovo M11', 'Aqui la descripción', 1350000, 8000, 50000, 4, 5),
('ET001', 'Dell Aspire', 'Aqui la descripción', 2700000, 10000, 60000, 1, 3),
('EP002', 'Asus Tuf', 'Aqui la descripción', 3500000, 12000, 80000, 5, 4),
('EE001', 'HP Novo', 'Aqui la descripción', 3430000, 12000, 80000, 3, 2);

INSERT INTO imagenes VALUES
('acer001.png', 'EP001'),
('lenovo001.png', 'T002'),
('hp001.png', 'ET001'),
('asus001.png', 'EP002'),
('hp002.png', 'EE001');

---------------------------------------------------------------------------------------
-- Datos de ejemplo para FarmApp

USE FarmApp;

-- Inserción de roles
INSERT INTO roles VALUES
(null, 'Administrador'),
(null, 'Farmacéutico'),
(null, 'Auxiliar');

-- Inserción de géneros
INSERT INTO generos VALUES
(null, 'Hombre'),
(null, 'Mujer'),
(null, 'Otro');

INSERT INTO usuarios VALUES
('761', 'sebatian Lopez', 'sebas@uno.com', '1234', '3173640722','Calle 11 Norte', 1, 1),
('341', 'Natalia', 'Natalia@uno.com', '1234', '3107046950', 'Carrera 2 # 10-22', 3, 2),
('762', 'Duver ', 'Duver@uno.com', '1234', '3229407955', 'Carrera 8 # 11-34', 2, 1);

-- Inserción de marcas de medicamentos
INSERT INTO marcas VALUES
(null, 'Pfizer'),
(null, 'Bayer'),
(null, 'Sanofi'),
(null, 'Novartis'),
(null, 'Roche'),
(null, 'Abbott');

-- Inserción de categorías de medicamentos
INSERT INTO categorias VALUES
(null, 'Analgésicos'),
(null, 'Antibióticos'),
(null, 'Rehidratación oral'),
(null, 'Antigripales'),
(null, 'Vitaminas');

-- Inserción de proveedores
INSERT INTO proveedores VALUES
(null, 'Distribuidora FarmaPlus', '3101234567', 'Calle 10 # 23-45'),
(null, 'Laboratorios La Salud', '3209876543', 'Carrera 12 # 34-56');

-- Inserción de medicamentos
INSERT INTO medicamentos VALUES
('M001', 'Acetaminofen 500mg', 'Alivio del dolor y fiebre', 2500.00, 1, 1, 1),
('T002', 'Dolex Forte 500mg', 'Antibiótico de amplio espectro',4800.00, 2, 2, 2),
('M003', 'Electrolit', 'Suero de rehidratación oral', 8000.00, 3, 3, 1),
('T004', 'Advilmax', 'Alivio de síntomas gripales', 5200.00, 4, 4, 2),
('M005', 'Shot B', 'Suplemento multivitamico', 43500.00, 5, 5, 1);

INSERT INTO imagenes (id_medicamento_imagen, nombre_imagen, ruta_imagen, principal) VALUES 
(1, 'acetaminofen.jpg', 'assets/images/acetaminofen.jpg', TRUE),
(2, 'advilmax.jpg', 'assets/images/advilmax.jpg', TRUE),
(3, 'dolexfote.jpg', 'assets/images/dolexfote.jpg', TRUE),
(4, 'shotb.jpg', 'assets/images/shotb.jpg', TRUE);

INSERT INTO imagenes (nombre_imagen, id_medicamento_imagen) VALUES
('acetaminofen.png', 'M001'),
('dolexforte.png', 'T002'),
('electrolit.png', 'M003'),
('advilmax.png', 'T004'),
('shotb.png', 'M005'),
('Diosmectita.png','D006'),
('friotan.png','F007'),
('levotiroxina.png','L008');
