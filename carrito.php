<?php
require_once 'header.php';

// Verificar si hay productos en el carrito
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

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
    <h2>Tu Carrito de Compra</h2>

    <?php if (empty($carrito)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> El carrito está vacío.
        </div>
        <div class="mt-4">
            <a href="catalogo.php" class="btn btn-primary">
                <i class="bi bi-cart"></i> Ir al Catálogo
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($carrito as $item):
                        $subtotal = $item['precio'] * $item['cantidad'];
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td>
                            <img src="assets/images/<?= htmlspecialchars($item['imagen']) ?>" 
                                alt="<?= htmlspecialchars($item['nombre']) ?>" 
                                style="width: 80px; height: auto;">
                        </td>
                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                        <td>$<?= number_format($item['precio'], 0, ',', '.') ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="controllers/carrito_controller.php?action=update&id=<?= $item['id'] ?>&cantidad=<?= $item['cantidad'] - 1 ?>" class="btn btn-sm btn-outline-secondary me-2">
                                    <i class="bi bi-dash"></i>
                                </a>
                                <span><?= $item['cantidad'] ?></span>
                                <a href="controllers/carrito_controller.php?action=update&id=<?= $item['id'] ?>&cantidad=<?= $item['cantidad'] + 1 ?>" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="bi bi-plus"></i>
                                </a>
                            </div>
                        </td>
                        <td>$<?= number_format($subtotal, 0, ',', '.') ?></td>
                        <td>
                            <a href="controllers/carrito_controller.php?action=remove&id=<?= $item['id'] ?>" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total:</strong></td>
                        <td colspan="2"><strong>$<?= number_format($total, 0, ',', '.') ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="controllers/carrito_controller.php?action=clear" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Vaciar Carrito
            </a>
            <a href="confirmar_pedido.php" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Confirmar Pedido
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>