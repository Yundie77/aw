<?php
session_start();
require_once 'config.php';

$user_id = $_SESSION['user_id']; 

// --- Filtros ---
$tipoFilter = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$categoriaFilter = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'fecha_desc';
$orderBy = ($orden === 'fecha_asc') ? "ORDER BY g.fecha ASC, g.id ASC" : "ORDER BY g.fecha DESC, g.id DESC";

$conditions = "g.usuario_id = ?";
$params = [$user_id];
$types = "i";

if ($tipoFilter && in_array($tipoFilter, ['Ingreso', 'Gasto'])) {
  $conditions .= " AND g.tipo = ?";
  $params[] = $tipoFilter;
  $types .= "s";
}

if ($categoriaFilter) {
  $conditions .= " AND c.nombre = ?";
  $params[] = $categoriaFilter;
  $types .= "s";
}

if ($search) {
  $conditions .= " AND (g.comentario LIKE ? OR c.nombre LIKE ?)";
  $searchParam = "%" . $search . "%";
  $params[] = $searchParam;
  $params[] = $searchParam;
  $types .= "ss";
}

$sqlUltimosMov = "
  SELECT g.id, g.tipo, g.monto, g.fecha, g.comentario, c.nombre AS categoria
  FROM gastos g
  JOIN categorias c ON g.categoria_id = c.id
  WHERE $conditions
  $orderBy
  LIMIT 50
";

$stmt = $conn->prepare($sqlUltimosMov);
if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$sqlTipos = "SELECT DISTINCT tipo FROM gastos WHERE usuario_id = ?";
$stmtTipos = $conn->prepare($sqlTipos);
$stmtTipos->bind_param("i", $user_id);
$stmtTipos->execute();
$resultTipos = $stmtTipos->get_result();
$tiposArray = [];
while ($rowTipo = $resultTipos->fetch_assoc()) {
  $tiposArray[] = $rowTipo['tipo'];
}
$stmtTipos->close();

$sqlCategorias = "SELECT DISTINCT nombre FROM categorias ORDER BY nombre ASC";
$resCategorias = $conn->query($sqlCategorias);

// Funcion proporcioanada por chatGPT: explicada en gastos.php
ob_start();
?>


<h2 style="text-align:center;">Historial de Gastos</h2>

<form method="GET" id="filtrosForm" class="filtros">
  <div>
    <label for="tipo">Tipo:</label>
    <select name="tipo" id="tipo" onchange="this.form.submit()">
      <option value="">Todos</option>
      <?php foreach ($tiposArray as $tipoOpt): ?>
        <option value="<?php echo $tipoOpt; ?>" <?php if ($tipoOpt == $tipoFilter)
             echo "selected"; ?>>
          <?php echo $tipoOpt; ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label for="categoria">Categoría:</label>
    <select name="categoria" id="categoria" onchange="this.form.submit()">
      <option value="">Todas</option>
      <?php while ($rowCat = $resCategorias->fetch_assoc()): ?>
        <option value="<?php echo htmlspecialchars($rowCat['nombre']); ?>" <?php if ($rowCat['nombre'] == $categoriaFilter)
             echo "selected"; ?>>
          <?php echo htmlspecialchars($rowCat['nombre']); ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>
  <div>
    <label for="search">Buscar:</label>
    <input type="text" name="search" id="search" placeholder="Buscar por categoria o comentario" style="width:300px;
         value=" <?php echo htmlspecialchars($search); ?>" onkeyup="debounceSearch()">
  </div>
</form>

<table class="tabla-gastos">
  <tr>
    <th>Categoría</th>
    <th>Tipo</th>
    <th>Monto (€)</th>
    <th>Fecha</th>
    <th>Comentario</th>
    <th>Acciones</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
    <?php
    $simbolo = ($row['tipo'] === 'Gasto') ? '-' : '+';
    $montoFormateado = number_format($row['monto'], 2, ',', '.');
    ?>
    <tr>
      <td><?php echo htmlspecialchars($row['categoria']); ?></td>
      <td><?php echo htmlspecialchars($row['tipo']); ?></td>
      <td><?php echo $simbolo . $montoFormateado; ?> €</td>
      <td><?php echo htmlspecialchars($row['fecha']); ?></td>
      <td><?php echo htmlspecialchars($row['comentario']); ?></td>
      <td>
        <a href="editar_gasto.php?id=<?php echo $row['id']; ?>">Editar</a>
        <a href="eliminar_gasto.php?id=<?php echo $row['id']; ?>"
          onclick="return confirm('¿Está seguro de eliminar este registro?');">Eliminar</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<div style="text-align:center; margin:20px;">
  <a href="historial_gastos.php?orden=fecha_desc">Ordenar por Fecha (descendente)</a> |
  <a href="historial_gastos.php?orden=fecha_asc">Ordenar por Fecha (ascendente)</a>
</div>

<script src="js/filtros.js" defer></script>
<script src="js/debounce.js" defer></script>


<?php
// Funcion proporcioanada por chatGPT: explicada en gastos.php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Historial de Gastos";

$conn->close();

require_once RAIZ_APP . '/includes/vistas/plantilla/plantilla.php';