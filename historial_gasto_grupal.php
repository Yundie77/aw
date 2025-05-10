<?php
session_start();
require_once 'includes/config.php';

$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

$user_id = $_SESSION['user_id']; 
$grupo_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


use es\ucm\fdi\aw\GastosGrupales;

$gastosGrupales = new GastosGrupales($conn);
$result = $gastosGrupales->getGastosUsuarioGrupo($grupo_id, $user_id);
$nombreGrupo = $gastosGrupales->getNombreGrupo($grupo_id);

ob_start();
?>

<h2 style="text-align:center;">Historial de mis gastos en <?= htmlspecialchars($nombreGrupo) ?></h2>

<table class="tabla-gastos">
  <tr>
    <th>Monto (€)</th>
    <th>Fecha</th>
    <th>Comentario</th>
    <th>Acciones</th>
  </tr>
  <?php foreach ($result as $row): ?>
    <tr>
      <td><?php echo number_format($row['monto'], 2, ',', '.') . ' €'; ?></td>
      <td><?php echo htmlspecialchars($row['fecha']); ?></td>
      <td><?php echo htmlspecialchars($row['comentario']); ?></td>
      <td>
        <button class="btn-edit"
        data-id="<?= $row['id']; ?>"
        data-monto="<?= $row['monto']; ?>"
        data-fecha="<?= $row['fecha']; ?>"
        data-comentario="<?= htmlspecialchars($row['comentario'], ENT_QUOTES) ?>"
        onclick="openEditModal(this)">
             Editar
        </button>


        <a href="eliminar_gasto_grupal.php?id=<?php echo $row['id']; ?>&grupo_id=<?= $grupo_id ?>"
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
    use es\ucm\fdi\aw\FormularioEditarGastoGrupal;
    $form = new FormularioEditarGastoGrupal($grupo_id);
    echo $form->gestiona();
    ?>
  </div>
</div>

<script src="<?= RUTA_JS ?>editarGastoGrupal.js" defer></script>

<?php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Historial de Gastos Grupales";

require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';
?>
