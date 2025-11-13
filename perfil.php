<?php
require_once 'header.php';

if(!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">Mi Perfil</h3>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                    
                    <div class="mb-4">
                        <h5>Información Personal</h5>
                        <hr>
                        <p><strong>Identificación:</strong> <?= htmlspecialchars($_SESSION['usuario']->id_usuario) ?></p>
                        <p><strong>Nombre:</strong> <?= htmlspecialchars($_SESSION['usuario']->nombre_usuario) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['usuario']->email_usuario) ?></p>
                        <p><strong>Teléfono:</strong> <?= htmlspecialchars($_SESSION['usuario']->telefono_usuario) ?></p>
                        <p><strong>Dirección:</strong> <?= htmlspecialchars($_SESSION['usuario']->direccion_usuario) ?></p>
                        <p><strong>Rol:</strong> <?= htmlspecialchars($_SESSION['usuario']->nombre_rol) ?></p>
                    </div>
                    
                    <div class="text-center">
                        <a href="mis_pedidos.php" class="btn btn-primary">
                            <i class="bi bi-list-check"></i> Ver Mis Pedidos
                        </a>
                        <a href="logout.php" class="btn btn-outline-danger">
                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>