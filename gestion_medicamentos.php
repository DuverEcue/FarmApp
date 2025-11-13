<?php
session_start();
// Verificar si es administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

include '../header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Medicamentos - Administración</title>
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f39c12;
            --light: #ecf0f1;
            --dark: #34495e;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        
        .admin-panel {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .admin-panel h1 {
            color: var(--primary);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--light);
            padding-bottom: 10px;
        }
        
        .admin-menu {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .menu-item {
            padding: 12px 20px;
            background: var(--light);
            border-radius: 8px;
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .menu-item.active {
            background: var(--secondary);
            color: white;
        }
        
        .menu-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .page-title {
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title h2 {
            color: var(--primary);
            font-weight: 600;
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .btn-primary {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #219653;
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid #eaeaea;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            font-size: 14px;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .stock-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .stock-yes {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success);
            border: 1px solid rgba(39, 174, 96, 0.2);
        }
        
        .stock-no {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger);
            border: 1px solid rgba(231, 76, 60, 0.2);
        }
        
        .actions {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.3s;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .edit-btn {
            color: var(--secondary);
            background: rgba(52, 152, 219, 0.1);
        }
        
        .delete-btn {
            color: var(--danger);
            background: rgba(231, 76, 60, 0.1);
        }
        
        .edit-btn:hover {
            background: rgba(52, 152, 219, 0.2);
            transform: scale(1.1);
        }
        
        .delete-btn:hover {
            background: rgba(231, 76, 60, 0.2);
            transform: scale(1.1);
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            background-color: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: modalFade 0.3s;
        }
        
        @keyframes modalFade {
            from { opacity: 0; transform: translateY(-30px) scale(0.9); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        
        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--primary);
            color: white;
            border-radius: 12px 12px 0 0;
        }
        
        .modal-header h3 {
            font-weight: 600;
            margin: 0;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: white;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.3s;
        }
        
        .close-btn:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .modal-body {
            padding: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--secondary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success);
            border: 1px solid rgba(39, 174, 96, 0.2);
        }
        
        .alert-danger {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger);
            border: 1px solid rgba(231, 76, 60, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Panel de Administración -->
        <div class="admin-panel">
            <h1>Panel de Administración</h1>
            <div class="admin-menu">
                <a href="../admin_dashboard.php" class="menu-item">📊 Dashboard</a>
                <a href="gestion_medicamentos.php" class="menu-item active">💊 Medicamentos</a>
                <a href="../gestion_usuarios.php" class="menu-item">👥 Usuarios</a>
                <a href="../gestion_pedidos.php" class="menu-item">📦 Pedidos</a>
                <a href="../gestion_categorias.php" class="menu-item">📁 Categorías</a>
            </div>
        </div>

        <!-- Gestión de Medicamentos -->
        <div class="page-title">
            <h2>Gestión de Medicamentos</h2>
            <button class="btn btn-primary" id="addMedBtn">
                <i>➕</i> Agregar Medicamento
            </button>
        </div>

        <div class="card">
            <div id="alertMessage"></div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="medicamentosTable">
                    <!-- Los medicamentos se cargarán aquí dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para agregar/editar medicamento -->
    <div class="modal" id="medModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Agregar Medicamento</h3>
                <button class="close-btn" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="medForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="medId">ID del Medicamento</label>
                            <input type="text" id="medId" class="form-control" placeholder="Ej: T004, M001" required>
                        </div>
                        <div class="form-group">
                            <label for="medNombre">Nombre del Medicamento</label>
                            <input type="text" id="medNombre" class="form-control" placeholder="Ej: Advilmax, Dolex Forte" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="medPrecio">Precio ($)</label>
                            <input type="number" id="medPrecio" class="form-control" min="0" step="0.01" placeholder="Ej: 5200, 4800" required>
                        </div>
                        <div class="form-group">
                            <label for="medCategoria">Categoría</label>
                            <select id="medCategoria" class="form-control" required>
                                <option value="">Seleccione una categoría</option>
                                <option value="Antigripales">Antigripales</option>
                                <option value="Antibióticos">Antibióticos</option>
                                <option value="Vitaminas">Vitaminas</option>
                                <option value="Antiinflamatorios">Antiinflamatorios</option>
                                <option value="Analgésicos">Analgésicos</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="medStock">Estado de Stock</label>
                        <select id="medStock" class="form-control" required>
                            <option value="true">✅ Disponible</option>
                            <option value="false">❌ No disponible</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" id="cancelBtn">Cancelar</button>
                <button class="btn btn-success" id="saveMedBtn">Guardar Medicamento</button>
            </div>
        </div>
    </div>

    <script>
        // Datos iniciales de medicamentos
        let medicamentos = [
            { id: "T004", nombre: "Advilmax", precio: 5200, stock: true, categoria: "Antigripales" },
            { id: "T002", nombre: "Dolex Forte 500mg", precio: 4800, stock: true, categoria: "Antibióticos" },
            { id: "M005", nombre: "Shot B", precio: 43500, stock: true, categoria: "Vitaminas" },
            { id: "M003", nombre: "Electrolit", precio: 8000, stock: true, categoria: "Antiinflamatorios" },
            { id: "M001", nombre: "Acetaminofen 500mg", precio: 2500, stock: true, categoria: "Analgésicos" },
            { id: "L008", nombre: "Levotiroxina 100mca", precio: 9800, stock: true, categoria: "Vitaminas" },
            { id: "F007", nombre: "Friotan Crema", precio: 15900, stock: true, categoria: "Antiinflamatorios" },
            { id: "D006", nombre: "Diosmectita 3g", precio: 7500, stock: true, categoria: "Analgésicos" }
        ];

        // Elementos del DOM
        const medicamentosTable = document.getElementById('medicamentosTable');
        const medModal = document.getElementById('medModal');
        const modalTitle = document.getElementById('modalTitle');
        const medForm = document.getElementById('medForm');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const saveMedBtn = document.getElementById('saveMedBtn');
        const addMedBtn = document.getElementById('addMedBtn');
        const alertMessage = document.getElementById('alertMessage');

        // Variables para el modo de edición
        let isEditing = false;
        let currentEditId = null;

        // Cargar la tabla de medicamentos
        function cargarMedicamentos() {
            medicamentosTable.innerHTML = '';
            
            medicamentos.forEach(med => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td><strong>${med.id}</strong></td>
                    <td>${med.nombre}</td>
                    <td><strong>$${med.precio.toLocaleString()}</strong></td>
                    <td>
                        <span class="stock-status ${med.stock ? 'stock-yes' : 'stock-no'}">
                            ${med.stock ? '✅ Disponible' : '❌ Agotado'}
                        </span>
                    </td>
                    <td>${med.categoria}</td>
                    <td class="actions">
                        <button class="action-btn edit-btn" data-id="${med.id}" title="Editar">✏️</button>
                        <button class="action-btn delete-btn" data-id="${med.id}" title="Eliminar">🗑️</button>
                    </td>
                `;
                
                medicamentosTable.appendChild(row);
            });
            
            // Agregar event listeners a los botones de edición y eliminación
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = e.target.getAttribute('data-id');
                    editarMedicamento(id);
                });
            });
            
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = e.target.getAttribute('data-id');
                    eliminarMedicamento(id);
                });
            });
        }

        // Mostrar modal para agregar medicamento
        addMedBtn.addEventListener('click', () => {
            console.log('Botón Agregar clickeado - FUNCIONA');
            isEditing = false;
            modalTitle.textContent = 'Agregar Medicamento';
            medForm.reset();
            medModal.style.display = 'flex';
        });

        // Cerrar modal
        function cerrarModal() {
            medModal.style.display = 'none';
        }

        closeModal.addEventListener('click', cerrarModal);
        cancelBtn.addEventListener('click', cerrarModal);

        // Guardar medicamento (agregar o editar)
        saveMedBtn.addEventListener('click', () => {
            const id = document.getElementById('medId').value.trim();
            const nombre = document.getElementById('medNombre').value.trim();
            const precio = parseFloat(document.getElementById('medPrecio').value);
            const categoria = document.getElementById('medCategoria').value;
            const stock = document.getElementById('medStock').value === 'true';
            
            console.log('Guardando medicamento:', { id, nombre, precio, categoria, stock });
            
            // Validaciones básicas
            if (!id || !nombre || !precio || !categoria) {
                mostrarAlerta('❌ Por favor, complete todos los campos', 'danger');
                return;
            }
            
            if (precio <= 0) {
                mostrarAlerta('❌ El precio debe ser mayor a 0', 'danger');
                return;
            }
            
            if (isEditing) {
                // Modo edición
                const index = medicamentos.findIndex(med => med.id === currentEditId);
                if (index !== -1) {
                    medicamentos[index] = { id, nombre, precio, stock, categoria };
                    mostrarAlerta('✅ Medicamento actualizado correctamente', 'success');
                }
            } else {
                // Modo agregar
                if (medicamentos.some(med => med.id === id)) {
                    mostrarAlerta('❌ El ID del medicamento ya existe', 'danger');
                    return;
                }
                
                medicamentos.push({ id, nombre, precio, stock, categoria });
                mostrarAlerta('✅ Medicamento agregado correctamente', 'success');
            }
            
            cargarMedicamentos();
            cerrarModal();
        });

        // Editar medicamento
        function editarMedicamento(id) {
            console.log('Editando medicamento:', id);
            const medicamento = medicamentos.find(med => med.id === id);
            
            if (medicamento) {
                isEditing = true;
                currentEditId = id;
                modalTitle.textContent = 'Editar Medicamento';
                
                document.getElementById('medId').value = medicamento.id;
                document.getElementById('medNombre').value = medicamento.nombre;
                document.getElementById('medPrecio').value = medicamento.precio;
                document.getElementById('medCategoria').value = medicamento.categoria;
                document.getElementById('medStock').value = medicamento.stock.toString();
                
                medModal.style.display = 'flex';
            }
        }

        // Eliminar medicamento
        function eliminarMedicamento(id) {
            const medicamento = medicamentos.find(med => med.id === id);
            if (medicamento && confirm(`¿Está seguro de que desea eliminar el medicamento "${medicamento.nombre}" (${medicamento.id})?`)) {
                medicamentos = medicamentos.filter(med => med.id !== id);
                cargarMedicamentos();
                mostrarAlerta('✅ Medicamento eliminado correctamente', 'success');
            }
        }

        // Mostrar alerta
        function mostrarAlerta(mensaje, tipo) {
            alertMessage.innerHTML = `<div class="alert alert-${tipo}">${mensaje}</div>`;
            setTimeout(() => {
                alertMessage.innerHTML = '';
            }, 4000);
        }

        // Cerrar modal al hacer clic fuera
        window.addEventListener('click', (e) => {
            if (e.target === medModal) {
                cerrarModal();
            }
        });

        // Inicializar la aplicación
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Inicializando gestión de medicamentos...');
            cargarMedicamentos();
        });
    </script>

    <?php include '../footer.php'; ?>
</body>
</html>