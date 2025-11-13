<?php
require_once 'header.php';

if(!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once 'config/conexion.php';
$db = Conexion::connect();
$id_usuario = $_SESSION['usuario']->id_usuario;

// Obtener pedidos del usuario
$query = "SELECT p.* 
          FROM pedidos p 
          WHERE p.id_usuario_pedido = ? 
          ORDER BY p.fecha_pedido DESC";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id_usuario); // CORRECCIÓN: Cambié "s" por "i" para integer
$stmt->execute();
$pedidos = $stmt->get_result();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">Mis Pedidos</h3>
                    <p class="mb-0 mt-2">Bienvenido, <?= htmlspecialchars($_SESSION['usuario']->nombre_usuario) ?></p>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <?php if($pedidos->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th># Pedido</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Dirección Entrega</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($pedido = $pedidos->fetch_object()): ?>
                                        <tr>
                                            <td>#<?= $pedido->id_pedido ?></td>
                                            <td><?= date('d/m/Y', strtotime($pedido->fecha_pedido)) ?></td>
                                            <td>$<?= number_format($pedido->total_pedido, 0, ',', '.') ?></td>
                                            <td><?= htmlspecialchars($pedido->direccion_entrega) ?></td>
                                            <td>
                                                <span class="badge 
                                                    <?= $pedido->estado_pedido == 'entregado' ? 'bg-success' : '' ?>
                                                    <?= $pedido->estado_pedido == 'en camino' ? 'bg-warning' : '' ?>
                                                    <?= $pedido->estado_pedido == 'pendiente' ? 'bg-secondary' : '' ?>
                                                    <?= $pedido->estado_pedido == 'cancelado' ? 'bg-danger' : '' ?>">
                                                    <?= ucfirst($pedido->estado_pedido) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if(file_exists('detalle_pedido.php')): ?>
                                                    <a href="detalle_pedido.php?id=<?= $pedido->id_pedido ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i> Ver Detalle
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                                        <i class="bi bi-eye"></i> Ver Detalle
                                                    </button>
                                                    <small class="text-muted d-block">Próximamente</small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-cart-x" style="font-size: 3rem; color: #6c757d;"></i>
                            <h4 class="mt-3">No tienes pedidos realizados</h4>
                            <p class="text-muted">Cuando realices pedidos, aparecerán aquí.</p>
                            <a href="catalogo.php" class="btn btn-primary mt-2">
                                <i class="bi bi-cart"></i> Ir de Compras
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer text-center">
                    <a href="perfil.php" class="btn btn-outline-primary me-2">
                        <i class="bi bi-person"></i> Volver a Mi Perfil
                    </a>
                    <a href="catalogo.php" class="btn btn-primary">
                        <i class="bi bi-bag"></i> Seguir Comprando
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>