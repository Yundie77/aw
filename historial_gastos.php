<?php
session_start();
require_once 'includes/config.php';

$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

$user_id = $_SESSION['user_id']; 

use es\ucm\fdi\aw\Gastos;
use es\ucm\fdi\aw\Categorias;

$gastos = new Gastos($conn);
$categorias = new Categorias($conn);

// --- Filtros ---
$tipoFilter = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$categoriaFilter = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'fecha_desc';

$orderBy = ($orden === 'fecha_asc') ? "ASC" : "DESC";
$limit = 50;

$result = $gastos->getFilteredMovimientos($user_id, $tipoFilter, $categoriaFilter, $search, $orderBy, $limit);

$tiposArray = $gastos->getTipos($user_id);

$resCategorias = $categorias->getAll();

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
      <option value="">-- Seleccione --</option>
      <?php foreach ($resCategorias as $rowCat): ?>
        <option value="<?php echo htmlspecialchars($rowCat['nombre']); ?>" <?php if ($rowCat['nombre'] == $categoriaFilter) echo "selected"; ?>>
          <?php echo htmlspecialchars($rowCat['nombre']); ?>
        </option>
      <?php endforeach; ?>
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
  <?php foreach ($result as $row): ?>
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
        <button class="btn-edit" data-id="<?php echo $row['id']; ?>" 
                data-tipo="<?php echo htmlspecialchars($row['tipo']); ?>" 
                data-monto="<?php echo $row['monto']; ?>" 
                data-fecha="<?php echo $row['fecha']; ?>" 
                data-comentario="<?php echo htmlspecialchars($row['comentario']); ?>"
                onclick="openEditModal(this)">
          Editar
        </button>
        <a href="eliminar_gasto.php?id=<?php echo $row['id']; ?>"
          onclick="return confirm('¿Está seguro de eliminar este registro?');">Eliminar</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<!-- Modal editar gasto -->
<div class="modal" id="editGastoModal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('editGastoModal')">&times;</span>
    <h2>Editar Gasto</h2>
    <?php
    use es\ucm\fdi\aw\FormularioEditarGasto;
    $form = new FormularioEditarGasto();
    echo $form->gestiona();
    ?>
  </div>
</div>

<div style="text-align:center; margin:20px;">
  <a href="historial_gastos.php?orden=fecha_desc">Ordenar por Fecha (descendente)</a> |
  <a href="historial_gastos.php?orden=fecha_asc">Ordenar por Fecha (ascendente)</a>
</div>

<script src="js/filtros.js" defer></script>
<script src="js/debounce.js" defer></script>
<script src="js/editarGastos.js" defer></script>

<?php
// Funcion proporcioanada por chatGPT: explicada en gastos.php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Historial de Gastos";

$conn->close();

require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';