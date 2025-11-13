<?php
require_once 'header.php';

// Verificar si hay un ID de pedido
$id_pedido = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Redirigir si no hay ID de pedido o mensaje de éxito
if ($id_pedido == 0 && !isset($_SESSION['pedido_exitoso'])) {
    header("Location: index.php");
    exit;
}

// Obtener información del pedido si hay ID
$pedido = null;
if ($id_pedido > 0) {
    require_once 'config/conexion.php';
    $db = Conexion::connect();
    
    $query = "SELECT * FROM pedidos WHERE id_pedido = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id_pedido);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $pedido = $result->fetch_object();
    }
}

// Limpiar mensaje de sesión
if (isset($_SESSION['pedido_exitoso'])) {
    unset($_SESSION['pedido_exitoso']);
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle" style="width: 80px; height: 80px;">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 40px;"></i>
                        </div>
                    </div>
                    
                    <h2 class="mb-4">¡Pedido Confirmado!</h2>
                    
                    <p class="mb-4">
                        Tu pedido ha sido procesado correctamente. Hemos recibido tu solicitud y la estamos procesando.
                        Recibirás una confirmación por correo electrónico con los detalles de tu compra.
                    </p>
                    
                    <?php if ($pedido): ?>
                        <div class="mb-4 text-start bg-light p-3 rounded">
                            <p class="mb-1"><strong>Número de pedido:</strong> #<?= $id_pedido ?></p>
                            <p class="mb-1"><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pedido->fecha_pedido)) ?></p>
                            <p class="mb-1"><strong>Total:</strong> $<?= number_format($pedido->total_pedido, 0, ',', '.') ?></p>
                            <p class="mb-0"><strong>Estado:</strong> <?= $pedido->estado_pedido ?></p>
                        </div>
                    <?php else: ?>
                        <div class="mb-4 text-start bg-light p-3 rounded">
                            <p class="mb-1"><strong>Número de pedido:</strong> #<?= rand(10000, 99999) ?></p>
                            <p class="mb-0"><strong>Fecha:</strong> <?= date('d/m/Y H:i') ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-grid gap-2">
                        <a href="index.php" class="btn btn-primary">
                            <i class="bi bi-house-door"></i> Volver al Inicio
                        </a>
                        <a href="catalogo.php" class="btn btn-outline-primary">
                            <i class="bi bi-cart"></i> Seguir Comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>