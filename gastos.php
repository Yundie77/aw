<?php
session_start();
require 'includes/vistas/comun/header.php';
require 'includes/vistas/comun/nav.php';
require_once 'config.php';

// Suponiendo que un usuario logueado con ID=1
$user_id = 1; // Cambia por $_SESSION['usuario_id'] si tenemos login

/**********************************************
 * 1. Cálculo de ingresos totales
 **********************************************/
$sqlIngresosTotales = "
  SELECT IFNULL(SUM(monto), 0) AS total_ingresos
  FROM gastos
  WHERE usuario_id = ? AND tipo = 'Ingreso'
";
$stmt = $conn->prepare($sqlIngresosTotales);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$ingresosTotales = $row['total_ingresos'];
$stmt->close();

/**********************************************
 * 2. Cálculo de gastos totales
 **********************************************/
$sqlGastosTotales = "
  SELECT IFNULL(SUM(monto), 0) AS total_gastos
  FROM gastos
  WHERE usuario_id = ? AND tipo = 'Gasto'
";
$stmt = $conn->prepare($sqlGastosTotales);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$gastosTotales = $row['total_gastos'];
$stmt->close();

/**********************************************
 * 3. Ingresos este mes
 **********************************************/
$sqlIngresosMes = "
  SELECT IFNULL(SUM(monto), 0) AS ingresos_mes
  FROM gastos
  WHERE usuario_id = ?
    AND tipo = 'Ingreso'
    AND MONTH(fecha) = MONTH(CURDATE())
    AND YEAR(fecha) = YEAR(CURDATE())
";
$stmt = $conn->prepare($sqlIngresosMes);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$ingresosMes = $row['ingresos_mes'];
$stmt->close();

/**********************************************
 * 4. Gastos este mes
 **********************************************/
$sqlGastosMes = "
  SELECT IFNULL(SUM(monto), 0) AS gastos_mes
  FROM gastos
  WHERE usuario_id = ?
    AND tipo = 'Gasto'
    AND MONTH(fecha) = MONTH(CURDATE())
    AND YEAR(fecha) = YEAR(CURDATE())
";
$stmt = $conn->prepare($sqlGastosMes);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$gastosMes = $row['gastos_mes'];
$stmt->close();

/**********************************************
 * 5. Últimos movimientos (limit 5)
 **********************************************/
$sqlUltimosMov = "
  SELECT g.tipo,
         g.monto,
         g.fecha,
         g.comentario,
         c.nombre AS categoria
  FROM gastos g
  JOIN categorias c ON g.categoria_id = c.id
  WHERE g.usuario_id = ?
  ORDER BY g.fecha DESC, g.id DESC
  LIMIT 5
";
$stmt = $conn->prepare($sqlUltimosMov);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$ultimos = $stmt->get_result();
$stmt->close();

/**********************************************
 * 6. Datos para el donut chart: Agrupar gastos por categoría
 **********************************************/
$sqlDonut = "
  SELECT c.nombre AS categoria, SUM(g.monto) AS total_categoria
  FROM gastos g
  JOIN categorias c ON g.categoria_id = c.id
  WHERE g.usuario_id = ? AND g.tipo = 'Gasto'
  GROUP BY g.categoria_id
";
$stmt = $conn->prepare($sqlDonut);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resDonut = $stmt->get_result();

$donutData = [];
while ($row = $resDonut->fetch_assoc()) {
  $donutData[] = $row;
}
$stmt->close();

/**********************************************
 * 7. Datos para el bar chart: Ingresos y Gastos por mes
 **********************************************/
$sqlBar = "
  SELECT 
    YEAR(fecha) AS anio,
    MONTH(fecha) AS mes,
    SUM(CASE WHEN tipo = 'Ingreso' THEN monto ELSE 0 END) AS total_ingreso,
    SUM(CASE WHEN tipo = 'Gasto' THEN monto ELSE 0 END) AS total_gasto
  FROM gastos
  WHERE usuario_id = ?
  GROUP BY YEAR(fecha), MONTH(fecha)
  ORDER BY YEAR(fecha), MONTH(fecha)
";
$stmt = $conn->prepare($sqlBar);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resBar = $stmt->get_result();

$barData = [];
while($row = $resBar->fetch_assoc()){
    $barData[] = $row;
}
$stmt->close();

/**********************************************
 * 8. Obtenemos todas las categorías de la tabla `categorias`
 **********************************************/
$sqlCat = "SELECT id, nombre FROM categorias ORDER BY nombre ASC";
$stmt = $conn->prepare($sqlCat);
$stmt->execute();
$resCat = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Gestión de Gastos</title>
  <link rel="stylesheet" href="../css/style.css">
  <!-- Incluimos Chart.js desde CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  <div class="container-f1"><!-- Contenedor general para la funcionalidad F1 -->
    <!-- 1. Resumen de ingresos/gastos en la parte superior -->
    <div class="summary">
      <div class="box ingreso-total">
        <p class="cantidad">+<?php echo number_format($ingresosTotales, 2, ',', '.'); ?> €</p>
        <p class="etiqueta">Ingresos Totales</p>
      </div>
      <div class="box gasto-total">
        <p class="cantidad">-<?php echo number_format($gastosTotales, 2, ',', '.'); ?> €</p>
        <p class="etiqueta">Gastos Totales</p>
      </div>
      <div class="box ingreso-mes">
        <p class="cantidad">+<?php echo number_format($ingresosMes, 2, ',', '.'); ?> €</p>
        <p class="etiqueta">Ingresos este mes</p>
      </div>
      <div class="box gasto-mes">
        <p class="cantidad">-<?php echo number_format($gastosMes, 2, ',', '.'); ?> €</p>
        <p class="etiqueta">Gastos este mes</p>
      </div>
    </div>

    <!-- 2. Zona principal: Donut Chart + Categorías con porcentajes + Últimos Movimientos -->
    <div class="main-content">
      <!-- a) Gráfico circular (Donut Chart) y lista de categorías -->
      <div class="chart-section">
        <h3>Gastos por Categoría</h3>
        <div class="chart-placeholder">
          <canvas id="donutChart" width="100" height="100"></canvas>
        </div>
        <!-- Lista dinámica de categorías con sus totales y porcentaje -->
        <ul class="lista-categorias">
          <?php foreach ($donutData as $item):
            $porcentaje = ($gastosTotales > 0) ? ($item['total_categoria'] / $gastosTotales) * 100 : 0;
            ?>
            <li>
              <?php echo htmlspecialchars($item['categoria']); ?>:
              -<?php echo number_format($item['total_categoria'], 2, ',', '.'); ?> €
              (<?php echo number_format($porcentaje, 2, ',', '.'); ?>%)
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- b) Últimos Movimientos y gráfico de barras -->
      <div class="last-movements">
        <h3>Últimos Movimientos</h3>
        <ul>
          <?php while ($mov = $ultimos->fetch_assoc()): ?>
            <li>
              <?php
              $simbolo = ($mov['tipo'] === 'Gasto') ? '-' : '+';
              $montoFormateado = number_format($mov['monto'], 2, ',', '.');
              $comentarioMostrar = !empty(trim($mov['comentario'])) ? $mov['comentario'] : $mov['categoria'];
              ?>
              <strong><?php echo $mov['categoria']; ?>:</strong>
              <?php echo $simbolo . $montoFormateado; ?>€
              (<?php echo $mov['fecha']; ?>)
              - <em><?php echo $comentarioMostrar; ?></em>
            </li>
          <?php endwhile; ?>
        </ul>
        <!-- Botón para ver todo el historial -->
        <button onclick="location.href='historial_gastos.php'">
          Ver historial completo
        </button>
        <!-- Agregamos el canvas para el gráfico de barras DEBAJO del botón -->
        <div class="bar-chart-section">
          <h3>Resumen Mensual</h3>
          <canvas id="barChart"></canvas>
        </div>
      </div>
    </div>

    <!-- 3. Formulario para registrar un nuevo gasto/ingreso -->
    <div class="form-section">
      <h3>Registrar Gasto/Ingreso</h3>
      <form action="procesar_gasto.php" method="POST">
        <div class="form-row">
          <label for="fecha">Fecha:</label>
          <input type="date" name="fecha" required value="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="form-row">
          <label for="tipo">Tipo:</label>
          <select name="tipo" required>
            <option value="Ingreso">Ingreso</option>
            <option value="Gasto">Gasto</option>
          </select>
        </div>
        <div class="form-row">
          <label for="categoria_id">Categoría:</label>
          <select name="categoria_id" id="categoriaSelect" required>
            <option value="">-- Seleccione --</option>
            <?php while ($cat = $resCat->fetch_assoc()): ?>
              <option value="<?php echo $cat['id']; ?>">
                <?php echo htmlspecialchars($cat['nombre']); ?>
              </option>
            <?php endwhile; ?>
            <option value="otra">Crear nueva categoría</option>
          </select>
          <!-- Campo para nueva categoría, inicialmente oculto -->
          <input type="text" name="categoria_nueva" id="categoriaNueva" placeholder="Escribe nueva categoría"
            style="display:none;">
        </div>
        <script src="js/categorias.js" defer></script>
        <script src="js/donutChart.js" defer></script>

        <span id="donutData" style="display: none;"><?php echo json_encode($donutData); ?></span>
        <span id="totalExpenses" style="display: none;"><?php echo $gastosTotales; ?></span>

        <div class="form-row">
          <label for="monto">Monto (€):</label>
          <input type="number" name="monto" step="0.01" required min="0">
        </div>
        <div class="form-row">
          <label for="comentario">Comentario:</label>
          <textarea name="comentario"></textarea>
        </div>
        <button type="submit">Registrar</button>
      </form>
    </div>
  </div><!-- Fin container-f1 -->

  <!-- Pasamos los datos de barData a JavaScript -->
  <script>
    var barData = <?php echo json_encode($barData); ?>;
  </script>
  <!-- Incluimos el archivo externo de JavaScript para el gráfico de barras -->
  <script src="js/barChart.js"></script>
</body>

</html>
<?php
$conn->close(); // Cierra la conexión al final
?>
