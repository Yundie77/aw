<?php
session_start();

// Conectamos la configuración y obtenemos el objeto de la aplicación y la conexión a la BD
require_once 'includes/config.php';
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

// Obtenemos el ID del grupo desde la URL
$group_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$group_id) {
    die("El grupo no está especificado.");
}

// Procesamos los datos para la sección "Uso" – gastos por categoría
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

// Procesamos los datos para la sección "Balance" – gastos por participantes
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
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php
  // Contenedor principal para los detalles de la página "Gastos"
  ?>
  <div class="details-container-gastos">

    <?php
    // Sección "Uso": gastos por categoría
    ?>
    <div class="seccion-container">
      <h3 class="highlight">Uso</h3>
      <ul>
        <?php if (count($usoResults) > 0): ?>
          <?php foreach ($usoResults as $uso): ?>
            <li>
              <?php echo htmlspecialchars($uso['categoria']); ?>: 
              <?php echo htmlspecialchars($uso['total']); ?> €
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>No hay datos</li>
        <?php endif; ?>
      </ul>
    </div>

    <?php
    // Sección "Balance": gastos por participantes
    ?>
    <div class="seccion-container">
      <h3 class="highlight">Balance</h3>
      <ul>
        <?php if (count($balanceResults) > 0): ?>
          <?php foreach ($balanceResults as $balance): ?>
            <li>
              <?php echo htmlspecialchars($balance['nombre']); ?>: 
              <?php echo htmlspecialchars($balance['total']); ?> €
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>No hay datos</li>
        <?php endif; ?>
      </ul>
    </div>

    <?php
    // Barra lateral con botones para cambiar entre "Objetivo" y "Gastos"
    ?>
    <div class="buttons-sidebar-gastos">
      <a href="grupo_detalles.php?id=<?php echo $group_id; ?>">
        <button>Objetivo</button>
      </a>
      <a href="grupo_detalles_gastos.php?id=<?php echo $group_id; ?>">
        <button class="selected">Gastos</button>
      </a>
    </div>

  </div>
</body>
</html>

<?php
// Funcion proporcioanada por chatGPT: explicada en gastos.php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Grupo Detalles Gastos: " . htmlspecialchars($group['nombre']);
require __DIR__ . '/includes/vistas/plantilla/plantilla.php';