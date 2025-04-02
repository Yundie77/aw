<?php
session_start();
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para acceder a esta funcionalidad.";
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/includes/clases/FormularioGasto.php';
require_once 'includes/clases/Gastos.php';

$user_id = $_SESSION['user_id'];
$gastosObj = new Gastos($conn);

$ingresosTotales = $gastosObj->getTotalIngresos($user_id);
$gastosTotales   = $gastosObj->getTotalGastos($user_id);
$ingresosMes     = $gastosObj->getIngresosMes($user_id);
$gastosMes       = $gastosObj->getGastosMes($user_id);
$ultimosMovimientos = $gastosObj->getUltimosMovimientos($user_id, 5);
$donutData       = $gastosObj->getDonutData($user_id);
$barData         = $gastosObj->getBarData($user_id);

// Funcion proporcioanada por chatGPT: 
// Inicia un búfer de salida. A partir de este punto, 
// cualquier contenido que normalmente se enviaría al navegador
//  se almacena en un búfer temporal en la memoria.
ob_start();
?>
<div class="container-f1"><!-- Contenedor general -->
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
      <script type="application/json" id="donutData">
        <?php echo json_encode($donutData); ?>
      </script>
      <span id="totalExpenses" style="display:none;">
        <?php echo $gastosTotales; ?>
      </span>
      <script src="js/donutChart.js"></script>
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
        <?php foreach ($ultimosMovimientos as $mov): ?>
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
        <?php endforeach; ?>
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
    <?php
      $form = new FormularioGasto();
      echo $form->gestiona();
    ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    var barData = <?php echo json_encode($barData); ?>;
  </script>
  <script src="js/barChart.js"></script>

</div><!-- Fin container-f1 -->


<?php
// Funcion proporcioanada por chatGPT: 
// Obtiene el contenido del búfer de salida y lo limpia. 
// Esto significa que el contenido capturado no se envía al navegador, 
// sino que se devuelve como una cadena para que pueda usarlo en el código.
$contenidoPrincipal = ob_get_clean();

$tituloPagina = "Gestión de Gastos";

$conn->close();

require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';
?>