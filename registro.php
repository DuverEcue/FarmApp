<?php
require_once 'header.php';
require_once 'config/conexion.php';

// Obtener géneros de la base de datos
$db = Conexion::connect();
$query_generos = "SELECT * FROM generos";
$generos = $db->query($query_generos);
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">Registro de usuario</h3>
                </div>
                <div class="card-body p-4">
                    <?php
                    // Mostrar mensaje de error si existe
                    if(isset($_SESSION['error_registro'])){
                        echo '<div class="alert alert-danger">'.$_SESSION['error_registro'].'</div>';
                        unset($_SESSION['error_registro']);
                    }
                    
                    // Mostrar mensaje de éxito si existe
                    if(isset($_SESSION['registro'])){
                        echo '<div class="alert alert-success">'.$_SESSION['registro'].'</div>';
                        unset($_SESSION['registro']);
                    }
                    ?>
                    
                    <form action="controllers/usuario_controller.php" method="post">
                        <input type="hidden" name="action" value="registro">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_usuario" class="form-label">Identificación</label>
                                <input type="text" class="form-control" id="id_usuario" name="id_usuario" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="genero" class="form-label">Género</label>
                                <select class="form-select" id="genero" name="genero" required>
                                    <option value="">Seleccionar</option>
                                    <?php while($genero = $generos->fetch_object()): ?>
                                        <option value="<?= $genero->id_genero ?>"><?= htmlspecialchars($genero->nombre_genero) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Registrarse</button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <p>¿Ya tienes una cuenta? <a href="login.php" class="text-decoration-none">Inicia sesión aquí</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
