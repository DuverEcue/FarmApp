<?php
session_start();

// Verificar si es administrador
if(!isset($_SESSION['usuario']) || $_SESSION['usuario']->id_rol_usuario != 1) {
    $_SESSION['error'] = 'No tienes permisos para acceder al panel de administración';
    header("Location: ../index.php");
    exit;
}

require_once '../config/conexion.php';
$db = Conexion::connect();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmApp - Gestión de Categorías</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }
        .sidebar .nav-link {
            color: #333;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #216ab3;
            color: white;
        }
        .header-admin {
            background: linear-gradient(135deg, #216ab3 0%, #1a5a9a 100%);
            color: white;
        }
        .main-content {
            margin-top: 20px;
        }
        .badge-count {
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    <!-- Header simple para admin -->
    <nav class="navbar navbar-dark header-admin">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="bi bi-tags me-2"></i> Gestión de Categorías
            </span>
            <div class="d-flex">
                <a href="../index.php" class="btn btn-outline-light btn-sm me-2">
                    <i class="bi bi-house me-1"></i> Ir al Sitio
                </a>
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i><?= $_SESSION['usuario']->nombre_usuario ?? 'Usuario' ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="../perfil.php"><i class="bi bi-person me-2"></i>Mi perfil</a></li>
                        <li><a class="dropdown-item" href="../mis_pedidos.php"><i class="bi bi-clipboard-check me-2"></i>Mis pedidos</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 bg-light sidebar">
                <div class="position-sticky pt-3">
                    <h5 class="text-center mb-4">Panel de Administración</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="index.php">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="medicamentos.php">
                                <i class="bi bi-capsule me-2"></i>Medicamentos
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="usuarios.php">
                                <i class="bi bi-people me-2"></i>Usuarios
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="pedidos.php">
                                <i class="bi bi-clipboard-check me-2"></i>Pedidos
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link active" href="categorias.php">
                                <i class="bi bi-tags me-2"></i>Categorías
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Gestión de Categorías</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="badge bg-primary">Administrador</span>
                    </div>
                </div>

                <!-- Contenido de categorías -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Lista de Categorías</h5>
                        <button class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Agregar Categoría
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Medicamentos</th>
                                        <th>Fecha Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT c.*, 
                                             COUNT(m.id_medicamento) as total_medicamentos
                                             FROM categorias c 
                                             LEFT JOIN medicamentos m ON c.id_categoria = m.id_categoria_medicamento 
                                             GROUP BY c.id_categoria 
                                             ORDER BY c.id_categoria DESC";
                                    $categorias = $db->query($query);
                                    
                                    if($categorias->num_rows > 0):
                                        while($cat = $categorias->fetch_object()):
                                            // CORRECCIÓN: Verificar si las propiedades existen antes de usarlas
                                            $descripcion = isset($cat->descripcion_categoria) ? $cat->descripcion_categoria : 'Sin descripción';
                                            $fecha_creacion = isset($cat->fecha_creacion) ? $cat->fecha_creacion : date('Y-m-d');
                                            $total_medicamentos = isset($cat->total_medicamentos) ? $cat->total_medicamentos : 0;
                                    ?>
                                    <tr>
                                        <td><strong>#<?= $cat->id_categoria ?></strong></td>
                                        <td>
                                            <strong><?= htmlspecialchars($cat->nombre_categoria) ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($descripcion) ?></td>
                                        <td>
                                            <span class="badge bg-primary badge-count">
                                                <?= $total_medicamentos ?> medicamentos
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($fecha_creacion)) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1" title="Editar categoría">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info me-1" title="Ver medicamentos">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" title="Eliminar categoría">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-tags" style="font-size: 2rem;"></i>
                                            <p class="mt-2">No hay categorías registradas</p>
                                            <p class="text-muted small">Las categorías te ayudan a organizar tus medicamentos</p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4>
                                    <?php
                                    $query = "SELECT COUNT(*) as total FROM categorias";
                                    $result = $db->query($query);
                                    echo $result->fetch_object()->total;
                                    ?>
                                </h4>
                                <p class="mb-0">Total Categorías</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>
                                    <?php
                                    $query = "SELECT COUNT(DISTINCT m.id_categoria_medicamento) as total 
                                              FROM medicamentos m 
                                              WHERE m.id_categoria_medicamento IS NOT NULL";
                                    $result = $db->query($query);
                                    echo $result->fetch_object()->total;
                                    ?>
                                </h4>
                                <p class="mb-0">Categorías en Uso</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4>
                                    <?php
                                    $query = "SELECT COUNT(*) as total FROM categorias c 
                                              LEFT JOIN medicamentos m ON c.id_categoria = m.id_categoria_medicamento 
                                              WHERE m.id_medicamento IS NULL";
                                    $result = $db->query($query);
                                    echo $result->fetch_object()->total;
                                    ?>
                                </h4>
                                <p class="mb-0">Categorías Vacías</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>Información sobre Categorías
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>¿Para qué sirven las categorías?</h6>
                                <ul class="small">
                                    <li>Organizan los medicamentos por tipo o función</li>
                                    <li>Facilitan la búsqueda y filtrado de productos</li>
                                    <li>Mejoran la experiencia de usuario en el catálogo</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Buenas prácticas:</h6>
                                <ul class="small">
                                    <li>Usa nombres claros y descriptivos</li>
                                    <li>Mantén una estructura lógica de categorías</li>
                                    <li>Evita categorías con pocos productos</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>