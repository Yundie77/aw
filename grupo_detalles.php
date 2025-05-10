<?php
use es\ucm\fdi\aw\FormularioGastoGrupo;
require_once 'includes/config.php';
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

if (!isset($_SESSION['user_id'])) {
    \es\ucm\fdi\aw\Aplicacion::redirige('login.php');
}

setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp');

$grupo_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$grupo_id) {
    die("El grupo no está especificado.");
}


$gastosObj = new \es\ucm\fdi\aw\GastosGrupales($conn);
$gastosGrafico = $gastosObj->getGastosPorParticipanteGrafico($grupo_id);
$nombreGrupo = $gastosObj->getNombreGrupo($grupo_id);


ob_start();
?>

<h2 style="text-align:center;"><?= htmlspecialchars($nombreGrupo) ?></h2>


<div class="graficos-row" style="display: flex; gap: 3rem; margin-top: 2rem; padding: 0 2rem;">
    <!-- Gráfico -->
    <div class="grafico-card" style="flex: 3; background: #fff; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <h3 style="text-align: center;">Gastos grupales</h3>
        <div class="grafico-container" style="margin: 1rem 0;">
            <canvas id="graficoParticipantes"></canvas>
        </div>
        <div class="grafico-descripcion" style="text-align: center; color: #555;">
            <p>Visualiza los gastos realizados por cada uno de los integrantes</p>
        </div>
    </div>

    <!-- Botones laterales -->
    <div class="grafico-botones" style="flex: 1; display: flex; flex-direction: column; justify-content: flex-start; gap: 1.5rem; padding-top: 2rem;">
        <a href="grupo_balance.php?id=<?= htmlspecialchars($grupo_id) ?>" style="text-decoration: none;">
            <button style="width: 100%; padding: 0.75rem; background-color: #4CAF50; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer;">Ver Balance</button>
        </a>
        
        <div class="form-section">
        <h3>Registrar Gasto</h3>
        <?php
         $form = new FormularioGastoGrupo($grupo_id);
         echo $form->gestiona();
        ?>   
  </div>
  <a href="historial_gasto_grupal.php?id=<?= htmlspecialchars($grupo_id) ?>" style="text-decoration: none;">
            <button style="width: 100%; padding: 0.75rem; background-color: #4CAF50; color: black; border: none; border-radius: 8px; font-size: 16px; cursor: pointer;">Editar mis Gastos</button>
        </a>
    </div>
</div>



<!-- script para pasar datos a Javascript sacados de ChatGPT que usa jsdelivr una herramienta gratis de js que usamos para la creacion de graficas -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const datosParticipantes = <?= json_encode($gastosGrafico) ?>;
</script>
<script src="<?= RUTA_JS ?>/barrasGastos.js"></script>

<?php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Detalles de Grupo - CampusCash";

require __DIR__ . '/includes/vistas/plantilla/plantilla.php';
?>