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

// Obtenemos los detalles del grupo
$stmtGroup = $conn->prepare("SELECT * FROM grupos WHERE id = ?");
$stmtGroup->bind_param("i", $group_id);
$stmtGroup->execute();
$resultGroup = $stmtGroup->get_result();
$group = $resultGroup->fetch_assoc();
if (!$group) {
    die("Grupo no encontrado.");
}

// Obtenemos los participantes y sus sumas totales (de la tabla gastos_grupales)
$stmtParticipants = $conn->prepare("
  SELECT u.nombre, COALESCE(SUM(gm.monto), 0) AS total 
  FROM grupo_usuarios gu 
  INNER JOIN usuarios u ON gu.usuario_id = u.id 
  LEFT JOIN gastos_grupales gm ON gu.grupo_id = gm.grupo_id AND gu.usuario_id = gm.usuario_id 
  WHERE gu.grupo_id = ? 
  GROUP BY gu.usuario_id, u.nombre
");
$stmtParticipants->bind_param("i", $group_id);
$stmtParticipants->execute();
$resultParticipants = $stmtParticipants->get_result();
$participants = $resultParticipants->fetch_all(MYSQLI_ASSOC);

// Preparamos los datos para el gráfico: arreglos de etiquetas y valores
$chartLabels = json_encode(array_column($participants, 'nombre'));
$chartData   = json_encode(array_column($participants, 'total'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalles del Grupo</title>
  <link rel="stylesheet" href="css/style.css">
  <!-- Incluimos Chart.js desde CDN (manteniendo la versión actual) -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <!-- Contenedor principal de detalles -->
  <div class="details-container">
    
    <!-- Sidebar izquierdo: nombre del grupo y lista de participantes -->
    <div class="sidebar-container">
      <div class="details-header">
          <h2><?php echo htmlspecialchars($group['nombre']); ?></h2>
      </div>
      <div class="details-sidebar">
        <ul>
          <?php foreach ($participants as $p): ?>
            <li><?php echo htmlspecialchars($p['nombre']); ?>: <?php echo htmlspecialchars($p['total']); ?> €</li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <!-- Contenedor central: gráfico -->
    <div class="chart-container">
      <canvas id="detallesChart"></canvas>
    </div>

    <!-- Sidebar derecho: botones para cambiar entre "Objetivo" y "Gastos" -->
    <div class="buttons-sidebar">
      <a href="grupo_detalles.php?id=<?php echo $group_id; ?>"><button class="selected">Objetivo</button></a>
      <a href="grupo_detalles_gastos.php?id=<?php echo $group_id; ?>"><button>Gastos</button></a>
    </div>
  </div>
  
  <!-- Transferimos los datos para el gráfico a variables globales en JavaScript -->
  <script>
    const chartLabels = <?php echo $chartLabels; ?>;
    const chartData = <?php echo $chartData; ?>;
    console.log("chartLabels:", chartLabels, "chartData:", chartData);
  </script>
  
  <!-- Incluimos el archivo externo con el código para construir el gráfico -->
  <script src="js/detallesChart.js"></script>
  
  <?php require 'includes/vistas/comun/footer.php'; ?>
</body>
</html>