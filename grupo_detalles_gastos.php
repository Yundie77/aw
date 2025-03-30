<?php
session_start();
require 'includes/vistas/comun/header.php';
require 'includes/vistas/comun/nav.php';
require_once 'config.php';

// Obtenemos el ID del grupo desde la URL
$group_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$group_id) {
    die("El grupo no está especificado.");
}

// Obtenemos los datos para la sección "Uso" – gastos totales por categoría
$stmtUso = $conn->prepare("
    SELECT c.nombre AS categoria, SUM(gm.monto) AS total
    FROM gastos_grupales gm 
    INNER JOIN categorias c ON gm.categoria_id = c.id 
    WHERE gm.grupo_id = ? 
    GROUP BY gm.categoria_id
");
$stmtUso->bind_param("i", $group_id);
$stmtUso->execute();
$resultUso = $stmtUso->get_result();
$usoResults = $resultUso->fetch_all(MYSQLI_ASSOC);

// Obtenemos los datos para la sección "Balance" – montos de gastos por participantes
$stmtBalance = $conn->prepare("
    SELECT u.nombre, COALESCE(SUM(gm.monto), 0) AS total 
    FROM grupo_usuarios gu 
    INNER JOIN usuarios u ON gu.usuario_id = u.id 
    LEFT JOIN gastos_grupales gm ON gu.grupo_id = gm.grupo_id AND gu.usuario_id = gm.usuario_id 
    WHERE gu.grupo_id = ? 
    GROUP BY gu.usuario_id, u.nombre
");
$stmtBalance->bind_param("i", $group_id);
$stmtBalance->execute();
$resultBalance = $stmtBalance->get_result();
$balanceResults = $resultBalance->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalles del Grupo - Gastos</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- Contenedor principal de detalles para la página Gastos -->
  <div class="details-container-gastos">

    <!-- Sección: Uso -->
    <div class="seccion-container">
      <h3 class="highlight">Uso</h3>
      <ul>
        <?php if (count($usoResults) > 0): ?>
          <?php foreach ($usoResults as $uso): ?>
            <li><?php echo htmlspecialchars($uso['categoria']); ?>: <?php echo htmlspecialchars($uso['total']); ?> €</li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>No hay datos</li>
        <?php endif; ?>
      </ul>
    </div>

    <!-- Sección: Balance -->
    <div class="seccion-container">
      <h3 class="highlight">Balance</h3>
      <ul>
        <?php if (count($balanceResults) > 0): ?>
          <?php foreach ($balanceResults as $balance): ?>
            <li><?php echo htmlspecialchars($balance['nombre']); ?>: <?php echo htmlspecialchars($balance['total']); ?> €</li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>No hay datos</li>
        <?php endif; ?>
      </ul>
    </div>

    <!-- Barra lateral derecha: Botones para cambiar entre "Objetivo" y "Gastos" -->
    <div class="buttons-sidebar-gastos">
      <a href="grupo_detalles.php?id=<?php echo $group_id; ?>"><button>Objetivo</button></a>
      <a href="grupo_detalles_gastos.php?id=<?php echo $group_id; ?>"><button class="selected">Gastos</button></a>
    </div>

  </div>

  <?php require 'includes/vistas/comun/footer.php'; ?>
</body>
</html>