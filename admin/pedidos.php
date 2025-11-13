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
    <title>FarmApp - Gestión de Pedidos</title>
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
        .badge-estado {
            font-size: 0.8rem;
            padding: 0.4em 0.6em;
        }
    </style>
</head>
<body>
    <!-- Header simple para admin -->
    <nav class="navbar navbar-dark header-admin">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="bi bi-clipboard-check me-2"></i> Gestión de Pedidos
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
                            <a class="nav-link active" href="pedidos.php">
                                <i class="bi bi-clipboard-check me-2"></i>Pedidos
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="categorias.php">
                                <i class="bi bi-tags me-2"></i>Categorías
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Gestión de Pedidos</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="badge bg-primary">Administrador</span>
                    </div>
                </div>

                <!-- Filtros de pedidos -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Estado</label>
                                <select class="form-select">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en camino">En camino</option>
                                    <option value="entregado">Entregado</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha desde</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha hasta</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100">
                                    <i class="bi bi-funnel me-2"></i>Filtrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenido de pedidos -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Todos los Pedidos</h5>
                        <div>
                            <span class="badge bg-light text-dark me-2">
                                Total: 
                                <?php
                                $query = "SELECT COUNT(*) as total FROM pedidos";
                                $result = $db->query($query);
                                echo $result->fetch_object()->total;
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th># Pedido</th>
                                        <th>Cliente</th>
                                        <th>Total</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT p.*, u.nombre_usuario 
                                              FROM pedidos p 
                                              INNER JOIN usuarios u ON p.id_usuario_pedido = u.id_usuario 
                                              ORDER BY p.fecha_pedido DESC";
                                    $pedidos = $db->query($query);
                                    
                                    if($pedidos->num_rows > 0):
                                        while($pedido = $pedidos->fetch_object()):
                                            // CORRECCIÓN: Verificar si las propiedades existen antes de usarlas
                                            $estado = isset($pedido->estado_pedido) ? $pedido->estado_pedido : 'pendiente';
                                            $fecha_pedido = isset($pedido->fecha_pedido) ? $pedido->fecha_pedido : date('Y-m-d');
                                            $total_pedido = isset($pedido->total_pedido) ? $pedido->total_pedido : 0;
                                    ?>
                                    <tr>
                                        <td><strong>#<?= $pedido->id_pedido ?></strong></td>
                                        <td><?= htmlspecialchars($pedido->nombre_usuario) ?></td>
                                        <td><strong>$<?= number_format($total_pedido, 0, ',', '.') ?></strong></td>
                                        <td><?= date('d/m/Y', strtotime($fecha_pedido)) ?></td>
                                        <td>
                                            <span class="badge badge-estado 
                                                <?= $estado == 'entregado' ? 'bg-success' : '' ?>
                                                <?= $estado == 'en camino' ? 'bg-warning' : '' ?>
                                                <?= $estado == 'pendiente' ? 'bg-secondary' : '' ?>
                                                <?= $estado == 'cancelado' ? 'bg-danger' : '' ?>">
                                                <?= ucfirst($estado) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1" title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success me-1" title="Marcar como entregado">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning me-1" title="Cambiar estado">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" title="Cancelar pedido">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-clipboard-x" style="font-size: 2rem;"></i>
                                            <p class="mt-2">No hay pedidos registrados</p>
                                            <a href="../catalogo.php" class="btn btn-primary btn-sm mt-2">
                                                <i class="bi bi-cart"></i> Ir al Catálogo
                                            </a>
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
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4>
                                    <?php
                                    $query = "SELECT COUNT(*) as total FROM pedidos";
                                    $result = $db->query($query);
                                    echo $result->fetch_object()->total;
                                    ?>
                                </h4>
                                <p class="mb-0">Total Pedidos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body text-center">
                                <h4>
                                    <?php
                                    $query = "SELECT COUNT(*) as total FROM pedidos WHERE estado_pedido = 'pendiente'";
                                    $result = $db->query($query);
                                    echo $result->fetch_object()->total;
                                    ?>
                                </h4>
                                <p class="mb-0">Pendientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>
                                    <?php
                                    $query = "SELECT COUNT(*) as total FROM pedidos WHERE estado_pedido = 'entregado'";
                                    $result = $db->query($query);
                                    echo $result->fetch_object()->total;
                                    ?>
                                </h4>
                                <p class="mb-0">Entregados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4>
                                    <?php
                                    $query = "SELECT COALESCE(SUM(total_pedido), 0) as total FROM pedidos WHERE estado_pedido = 'entregado'";
                                    $result = $db->query($query);
                                    echo '$' . number_format($result->fetch_object()->total, 0, ',', '.');
                                    ?>
                                </h4>
                                <p class="mb-0">Ingresos Totales</p>
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