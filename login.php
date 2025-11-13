<?php
require_once 'header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">Iniciar sesión</h3>
                </div>
                <div class="card-body p-4">
                    <?php
                    // Mostrar mensaje de error si existe
                    if(isset($_SESSION['error_login'])){
                        echo '<div class="alert alert-danger">'.$_SESSION['error_login'].'</div>';
                        unset($_SESSION['error_login']);
                    }
                    
                    // Mostrar mensaje de éxito si existe
                    if(isset($_SESSION['registro'])){
                        echo '<div class="alert alert-success">'.$_SESSION['registro'].'</div>';
                        unset($_SESSION['registro']);
                    }
                    ?>
                    
                    <form action="controllers/usuario_controller.php" method="post">
                        <input type="hidden" name="action" value="login">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <p>¿No tienes una cuenta? <a href="registro.php" class="text-decoration-none">Regístrate aquí</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>