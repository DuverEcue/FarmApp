<?php
require_once 'header.php';

// Verificar si hay productos en el carrito
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    $_SESSION['error'] = 'Debes iniciar sesión para confirmar tu pedido';
    header("Location: login.php");
    exit;
}

// Redirigir si el carrito está vacío
if (empty($carrito)) {
    $_SESSION['error'] = 'No puedes confirmar un pedido con el carrito vacío';
    header("Location: carrito.php");
    exit;
}

// Calcular total
$total = 0;
foreach ($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

// Mostrar mensaje si existe
if(isset($_SESSION['mensaje'])) {
    echo '<div class="container mt-3"><div class="alert alert-success">'.$_SESSION['mensaje'].'</div></div>';
    unset($_SESSION['mensaje']);
}

if(isset($_SESSION['error'])) {
    echo '<div class="container mt-3"><div class="alert alert-danger">'.$_SESSION['error'].'</div></div>';
    unset($_SESSION['error']);
}
?>

<div class="container" style="padding: 20px;">
    <h2>Confirmar Pedido</h2>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información de Entrega</h5>
                </div>
                <div class="card-body">
                    <form action="controllers/carrito_controller.php" method="post">
                        <input type="hidden" name="action" value="crear_pedido">
                        
                        <div class="mb-3">
                            <label for="direccion_entrega" class="form-label">Dirección de Entrega</label>
                            <input type="text" class="form-control" id="direccion_entrega" name="direccion_entrega" 
                                   value="<?= htmlspecialchars($_SESSION['usuario']->direccion_usuario) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="telefono_contacto" class="form-label">Teléfono de Contacto</label>
                            <input type="text" class="form-control" id="telefono_contacto" name="telefono_contacto" 
                                   value="<?= htmlspecialchars($_SESSION['usuario']->telefono_usuario) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notas_adicionales" class="form-label">Notas Adicionales (opcional)</label>
                            <textarea class="form-control" id="notas_adicionales" name="notas_adicionales" rows="3"></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="carrito.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Volver al Carrito
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Confirmar Pedido
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($carrito as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="my-0"><?= htmlspecialchars($item['nombre']) ?></h6>
                                    <small class="text-muted">Cantidad: <?= $item['cantidad'] ?></small>
                                    <br>
                                    <small class="text-muted">Precio: $<?= number_format($item['precio'], 0, ',', '.') ?></small>
                                </div>
                                <span class="text-muted">$<?= number_format($item['precio'] * $item['cantidad'], 0, ',', '.') ?></span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (COP)</span>
                            <strong>$<?= number_format($total, 0, ',', '.') ?></strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>