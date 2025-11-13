<?php
require_once 'header.php';
require_once 'config/conexion.php';

// Obtener categorías
$db = Conexion::connect();
$query_categorias = "SELECT * FROM categorias";
$categorias = $db->query($query_categorias);

// Obtener medicamentos con filtrado opcional
$categoria_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

$sql = "SELECT m.*, i.nombre_imagen, c.nombre_categoria 
        FROM medicamentos m
        INNER JOIN imagenes i ON m.id_medicamento = i.id_medicamento_imagen
        INNER JOIN categorias c ON m.id_categoria_medicamento = c.id_categoria
        WHERE 1=1";

if ($categoria_id > 0) {
    $sql .= " AND m.id_categoria_medicamento = $categoria_id";
}

if (!empty($busqueda)) {
    $sql .= " AND (m.nombre_medicamento LIKE '%$busqueda%' OR m.descripcion_medicamento LIKE '%$busqueda%')";
}

$sql .= " GROUP BY m.id_medicamento";
$medicamentos = $db->query($sql);
?>

<div class="contenedor" style="padding: 20px;">
  <h2>Catálogo de Medicamentos</h2>
  
  <!-- Filtro y búsqueda -->
  <div class="Conthcj" style="margin: 20px 0;">
    <div class="Conthcl" style="width: 50%;">
      <form action="catalogo.php" method="get" class="Conthcl">
        <select name="categoria" onchange="this.form.submit()" style="margin-right: 10px;">
          <option value="0">Todas las categorías</option>
          <?php while($cat = $categorias->fetch_object()): ?>
            <option value="<?= $cat->id_categoria ?>" <?= $categoria_id == $cat->id_categoria ? 'selected' : '' ?>>
              <?= $cat->nombre_categoria ?>
            </option>
          <?php endwhile; ?>
        </select>
      </form>
    </div>
    
    <div class="Conthcr" style="width: 50%;">
      <form action="catalogo.php" method="get" class="Conthcr">
        <input type="text" name="busqueda" placeholder="Buscar medicamento..." value="<?= $busqueda ?>">
        <input type="submit" value="Buscar" class="Bt2">
      </form>
    </div>
  </div>
  
  <!-- Listado de medicamentos -->
  <div class="Conthcs" style="flex-wrap: wrap;">
    <?php if($medicamentos && $medicamentos->num_rows > 0): ?>
      <?php while($med = $medicamentos->fetch_object()): ?>
        <div class="product" style="margin: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
          <img src="assets/images/<?= $med->nombre_imagen ?>" alt="<?= $med->nombre_medicamento ?>" style="max-width: 150px; max-height: 150px;">
          <h3><?= $med->nombre_medicamento ?></h3>
          <p class="categoria"><?= $med->nombre_categoria ?></p>
          <p class="descripcion"><?= !empty($med->descripcion_medicamento) ? $med->descripcion_medicamento : 'Sin descripción' ?></p>
          <p class="precio">$<?= number_format($med->precio_medicamento, 2, ',', '.') ?></p>
          <a href="controllers/carrito_controller.php?action=add&id=<?= $med->id_medicamento ?>" class="Bt1">Agregar al carrito</a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No se encontraron medicamentos</p>
    <?php endif; ?>
  </div>
</div>

<?php require_once 'footer.php'; ?>
