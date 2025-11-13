<?php
session_start();

// Contar productos en el carrito
$carrito_count = 0;
if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
  $carrito_count = count($_SESSION['carrito']);
}

// Obtener la ruta base del proyecto
$base_url = '/FarmApp'; // Ajusta esto según tu configuración
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FarmApp - Tu Farmacia en Línea</title>
  <link rel="shortcut icon" href="assets/images/logo2.png">
  <link rel="stylesheet" href="assets/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    .header-container {
      background: linear-gradient(135deg, #216ab3 0%, #1a5a9a 100%);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .nav-link-custom {
      color: white !important;
      font-weight: 500;
      padding: 12px 20px !important;
      border-radius: 8px;
      transition: all 0.3s ease;
      text-decoration: none;
    }
    
    .nav-link-custom:hover {
      background: rgba(255,255,255,0.15);
      transform: translateY(-2px);
    }
    
    .user-dropdown {
      position: relative;
    }
    
    .user-btn {
      background: rgba(255,255,255,0.15);
      border: 2px solid rgba(255,255,255,0.3);
      color: white;
      padding: 8px 16px;
      border-radius: 25px;
      cursor: pointer;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
    }
    
    .user-btn:hover {
      background: rgba(255,255,255,0.25);
      border-color: rgba(255,255,255,0.5);
    }
    
    .dropdown-menu-custom {
      display: none;
      position: absolute;
      top: 100%;
      right: 0;
      background: white;
      min-width: 220px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      border-radius: 12px;
      padding: 10px 0;
      margin-top: 10px;
      z-index: 1000;
      border: none;
    }
    
    .dropdown-menu-custom.show {
      display: block;
      animation: fadeIn 0.3s ease;
    }
    
    .dropdown-item-custom {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      text-decoration: none;
      color: #333;
      transition: all 0.3s ease;
      border: none;
      background: none;
      width: 100%;
      text-align: left;
    }
    
    .dropdown-item-custom:hover {
      background: #f8f9fa;
      color: #216ab3;
    }
    
    .dropdown-item-custom.logout {
      color: #dc3545;
    }
    
    .dropdown-item-custom.logout:hover {
      background: #fff5f5;
      color: #dc3545;
    }
    
    .cart-container {
      position: relative;
      margin-right: 20px;
    }
    
    .cart-link {
      display: flex;
      align-items: center;
      text-decoration: none;
      color: white;
      font-weight: 500;
      padding: 8px 16px;
      border-radius: 25px;
      transition: all 0.3s ease;
      background: rgba(255,255,255,0.15);
    }
    
    .cart-link:hover {
      background: rgba(255,255,255,0.25);
      color: white;
      transform: translateY(-2px);
    }
    
    .cart-badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: #ff4444;
      color: white;
      border-radius: 50%;
      width: 22px;
      height: 22px;
      font-size: 12px;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px rgba(255,68,68,0.4);
    }
    
    .logo-container {
      margin-left: 20px;
      padding-left: 20px;
      border-left: 2px solid rgba(255,255,255,0.3);
    }
    
    .logo-img {
      height: 45px;
      transition: transform 0.3s ease;
      background: white;
      padding: 5px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    
    .logo-img:hover {
      transform: scale(1.05);
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .nav-link-custom {
        padding: 10px 15px !important;
        font-size: 14px;
      }
      
      .cart-link span {
        display: none;
      }
      
      .user-btn span {
        display: none;
      }
      
      .logo-container {
        margin-left: 10px;
        padding-left: 10px;
      }
    }
  </style>
</head>
<body>
  <!-- Header Mejorado -->
  <header class="header-container py-2">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <!-- Logo y Navegación -->
        <div class="d-flex align-items-center">
          <!-- Navegación Principal -->
          <nav class="d-flex align-items-center">
            <a href="index.php" class="nav-link-custom me-2">
              <i class="bi bi-house-door me-2"></i>Inicio
            </a>
            <a href="catalogo.php" class="nav-link-custom me-2">
              <i class="bi bi-capsule me-2"></i>Medicamentos
            </a>
            <?php if(isset($_SESSION['usuario']) && $_SESSION['usuario']->id_rol_usuario == 1): ?>
              <a href="admin/index.php" class="nav-link-custom me-2">
                <i class="bi bi-gear me-2"></i>Administración
              </a>
            <?php endif; ?>
          </nav>
        </div>

        <!-- Carrito, Usuario y Logo -->
        <div class="d-flex align-items-center">
          <!-- Carrito de Compras -->
          <div class="cart-container">
            <a href="carrito.php" class="cart-link">
              <i class="bi bi-cart3 me-2"></i>
              <span>Carrito</span>
              <?php if($carrito_count > 0): ?>
                <span class="cart-badge"><?= $carrito_count ?></span>
              <?php endif; ?>
            </a>
          </div>

          <!-- Menú de Usuario -->
          <?php if(isset($_SESSION['usuario'])): ?>
            <div class="user-dropdown ms-3">
              <button class="user-btn">
                <i class="bi bi-person-circle"></i>
                <span><?= htmlspecialchars(explode(' ', $_SESSION['usuario']->nombre_usuario)[0]) ?></span>
                <i class="bi bi-chevron-down"></i>
              </button>
              <div class="dropdown-menu-custom">
                <a href="perfil.php" class="dropdown-item-custom">
                  <i class="bi bi-person me-2"></i>Mi perfil
                </a>
                <a href="mis_pedidos.php" class="dropdown-item-custom">
                  <i class="bi bi-list-check me-2"></i>Mis pedidos
                </a>
                <div class="dropdown-divider"></div>
                <a href="logout.php" class="dropdown-item-custom logout">
                  <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                </a>
              </div>
            </div>
          <?php else: ?>
            <div class="d-flex gap-2 ms-3">
              <a href="login.php" class="nav-link-custom" style="background: rgba(255,255,255,0.1);">
                <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
              </a>
              <a href="registro.php" class="nav-link-custom" style="background: rgba(255,255,255,0.2);">
                <i class="bi bi-person-plus me-2"></i>Registrarse
              </a>
            </div>
          <?php endif; ?>

          <!-- Logo -->
          <div class="logo-container">
            <a href="index.php">
              <img src="assets/images/logo2.png" alt="FarmApp" class="logo-img">
            </a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- JavaScript para el dropdown -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const userDropdown = document.querySelector('.user-dropdown');
    const userBtn = document.querySelector('.user-btn');
    const dropdownMenu = document.querySelector('.dropdown-menu-custom');
    
    if(userBtn && dropdownMenu) {
      userBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
      });
      
      // Cerrar dropdown al hacer clic fuera
      document.addEventListener('click', function() {
        dropdownMenu.classList.remove('show');
      });
      
      // Prevenir que el dropdown se cierre al hacer clic dentro
      dropdownMenu.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    }
  });
  </script>