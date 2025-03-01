<?php
session_start();
require '../includes/header.php';
require '../includes/nav.php';
require_once '../config.php';

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
  SELECT tipo, categoria, monto, fecha, comentario
  FROM gastos
  WHERE usuario_id = ?
  ORDER BY fecha DESC, id DESC
  LIMIT 5
";
$stmt = $conn->prepare($sqlUltimosMov);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$ultimos = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Gestión de Gastos</title>
  <link rel="stylesheet" href="../css/style.css">
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

    <!-- 2. Zona principal: Gráfico (imagen) + Categorías + Últimos Movimientos -->
    <div class="main-content">
      <!-- a) Gráfico circular (simulado con imagen) y lista de categorías con totales -->
      <div class="chart-section">
        <h3>Gastos por Categoría</h3>
        <div class="chart-placeholder">
          <!-- Pon una imagen PNG que simule el donut -->
          <img src="../img/donut-chart.png" alt="Donut Chart" width="200">
        </div>
        <!-- EJEMPLO: Podrías hacer un SELECT SUM(ABS(monto)) GROUP BY categoria
           para listar lo que llevas gastado en cada categoría y mostrarlo aquí -->
        <ul class="lista-categorias">
          <li>Ropa: -78,00 €</li>
          <li>Comida: -79,00 €</li>
          <li>Ocio: -58,00 €</li>
          <!-- ... -->
        </ul>
      </div>

      <!-- b) Últimos Movimientos -->
      <div class="last-movements">
        <h3>Últimos Movimientos</h3>
        <ul>
        <?php while ($mov = $ultimos->fetch_assoc()): ?>
            <li>
              <?php
              // Determinar el símbolo según el tipo
              $simbolo = ($mov['tipo'] === 'Gasto') ? '-' : '+';
              // Formateamos el monto (siempre positivo en la BD)
              $montoFormateado = number_format($mov['monto'], 2, ',', '.');
              // Si no hay comentario, usamos la categoría por defecto
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
        <button onclick="location.href='../views/historial_gastos.php'">
          Ver historial completo
        </button>
      </div>
      </div>

    <!-- 3. Formulario para registrar un nuevo gasto/ingreso -->
    <div class="form-section">
      <h3>Registrar Gasto/Ingreso</h3>
      <form action="../controllers/procesar_gasto.php" method="POST">

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
          <label for="categoria">Categoría:</label>
          <select name="categoria" required>
            <option value="Ropa">Ropa</option>
            <option value="Comida">Comida</option>
            <option value="Ocio">Ocio</option>
            <option value="Salud">Salud</option>
            <option value="Transporte">Transporte</option>
            <!-- Añade las que necesites -->
          </select>
        </div>

        <div class="form-row">
          <label for="monto">Monto (€):</label>
          <input type="number" name="monto" step="0.5" required min="0">
        </div>

        <div class="form-row">
          <label for="comentario">Comentario:</label>

          <textarea name="comentario"></textarea>
        </div>
        <button type="submit">Registrar</button>
      </form>
    </div>

  </div><!-- Fin container-f1 -->

</body>

</html>
<?php
$conn->close(); // Cierra la conexión al final
?>