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
    <title>FarmApp - Panel de Administración</title>
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
    </style>
</head>
<body>
    <!-- Header simple para admin -->
    <nav class="navbar navbar-dark header-admin">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="bi bi-speedometer2 me-2"></i> Panel de Administración
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
                            <a class="nav-link active" href="index.php">
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
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="badge bg-primary">Administrador</span>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">
                                            <?php
                                            $query = "SELECT COUNT(*) as total FROM usuarios";
                                            $result = $db->query($query);
                                            echo $result->fetch_object()->total;
                                            ?>
                                        </h4>
                                        <p class="card-text">Usuarios</p>
                                    </div>
                                    <i class="bi bi-people fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">
                                            <?php
                                            $query = "SELECT COUNT(*) as total FROM medicamentos";
                                            $result = $db->query($query);
                                            echo $result->fetch_object()->total;
                                            ?>
                                        </h4>
                                        <p class="card-text">Medicamentos</p>
                                    </div>
                                    <i class="bi bi-capsule fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">
                                            <?php
                                            $query = "SELECT COUNT(*) as total FROM pedidos WHERE estado_pedido = 'pendiente'";
                                            $result = $db->query($query);
                                            echo $result->fetch_object()->total;
                                            ?>
                                        </h4>
                                        <p class="card-text">Pedidos Pendientes</p>
                                    </div>
                                    <i class="bi bi-clock fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">
                                            <?php
                                            $query = "SELECT COUNT(*) as total FROM pedidos";
                                            $result = $db->query($query);
                                            echo $result->fetch_object()->total;
                                            ?>
                                        </h4>
                                        <p class="card-text">Total Pedidos</p>
                                    </div>
                                    <i class="bi bi-clipboard-check fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Últimos pedidos -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Últimos Pedidos</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $query = "SELECT p.*, u.nombre_usuario 
                                          FROM pedidos p 
                                          INNER JOIN usuarios u ON p.id_usuario_pedido = u.id_usuario 
                                          ORDER BY p.fecha_pedido DESC 
                                          LIMIT 5";
                                $pedidos = $db->query($query);
                                ?>
                                
                                <?php if($pedidos->num_rows > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th># Pedido</th>
                                                    <th>Cliente</th>
                                                    <th>Total</th>
                                                    <th>Fecha</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($pedido = $pedidos->fetch_object()): ?>
                                                    <tr>
                                                        <td>#<?= $pedido->id_pedido ?></td>
                                                        <td><?= htmlspecialchars($pedido->nombre_usuario) ?></td>
                                                        <td>$<?= number_format($pedido->total_pedido, 0, ',', '.') ?></td>
                                                        <td><?= date('d/m/Y', strtotime($pedido->fecha_pedido)) ?></td>
                                                        <td>
                                                            <span class="badge 
                                                                <?= $pedido->estado_pedido == 'entregado' ? 'bg-success' : '' ?>
                                                                <?= $pedido->estado_pedido == 'en camino' ? 'bg-warning' : '' ?>
                                                                <?= $pedido->estado_pedido == 'pendiente' ? 'bg-secondary' : '' ?>
                                                                <?= $pedido->estado_pedido == 'cancelado' ? 'bg-danger' : '' ?>">
                                                                <?= ucfirst($pedido->estado_pedido) ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">No hay pedidos registrados.</p>
                                <?php endif; ?>
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