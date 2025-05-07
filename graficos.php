<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    \es\ucm\fdi\aw\Aplicacion::redirige('login.php');
}

setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp');

$user_id = $_SESSION['user_id'];

$graficosAnalisis = new \es\ucm\fdi\aw\GraficosAnalisis();
$datosLinea = $graficosAnalisis->getGastosMensuales($user_id);
$datosComparacion = $graficosAnalisis->getComparacionGastos($user_id);
$datosDispersion = $graficosAnalisis->getIngresosVsGastos($user_id);
$datosBarrasApiladas = $graficosAnalisis->getGastosPorCategoriaPorMes($user_id);

ob_start();
?>

<div class="graficos-container">
    <h1 class="titulo-graficos">Análisis de Gastos</h1>
    
    <div class="graficos-row">
        <!-- grafico 1: grafico de linea para gastos en tiempo -->
        <div class="grafico-card">
            <h3>Gastos a lo largo del tiempo</h3>
            <div class="grafico-container">
                <canvas id="lineChart"></canvas>
            </div>
            <div class="grafico-descripcion">
                <p>Visualiza tus patrones de gasto diarios o semanales a lo largo del tiempo para identificar tendencias.</p>
            </div>
        </div>
        
        <!-- grafico 2: grafico comparativo con gastos promedio -->
        <div class="grafico-card">
            <h3>Comparación con gastos promedio</h3>
            <div class="grafico-container">
                <canvas id="comparisonChart"></canvas>
            </div>
            <div class="grafico-descripcion">
                <p>Compara tu patrón de gastos con el promedio de otros estudiantes en cada categoría.</p>
            </div>
        </div>

        <!-- grafico 3: placeholder para futuro gráfico -->
        <div class="grafico-card">
            <h3>Gráfico futuro 1</h3>
            <div class="grafico-container">
                <!-- <canvas id="futureChart1"></canvas> -->
            </div>
            <div class="grafico-descripcion">
                <p>Espacio reservado para un nuevo gráfico.</p>
            </div>
        </div>

        <!-- grafico 4: placeholder para futuro gráfico -->
        <div class="grafico-card">
            <h3>Gráfico futuro 2</h3>
            <div class="grafico-container">
                <!-- <canvas id="futureChart2"></canvas> -->
            </div>
            <div class="grafico-descripcion">
                <p>Espacio reservado para un nuevo gráfico.</p>
            </div>
        </div>
    </div>
    
    <div class="graficos-row">
        <!-- grafico 3: grafico de dispersion para ingresos vs gastos -->
        <div class="grafico-card">
            <h3>Relación Ingresos - Gastos</h3>
            <div class="grafico-container">
                <canvas id="scatterChart"></canvas>
            </div>
            <div class="grafico-descripcion">
                <p>Analiza la relación entre tus ingresos y gastos mensuales para identificar meses con riesgo financiero.</p>
            </div>
        </div>
        
        <!-- grafico 4: grafico de barras apiladas para desglose de gastos -->
        <div class="grafico-card">
            <h3>Desglose de gastos por categoría</h3>
            <div class="grafico-container">
                <canvas id="stackedChart"></canvas>
            </div>
            <div class="grafico-descripcion">
                <p>Visualiza cómo se distribuyen tus gastos entre diferentes categorías a lo largo del tiempo.</p>
            </div>
        </div>
    </div>
</div>

<!-- script para pasar datos a Javascript sacados de ChatGPT que usa jsdelivr una herramienta gratis de js que usamos para la creacion de graficas -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var datosLinea = <?= json_encode($datosLinea) ?>;
    var datosComparacion = <?= json_encode($datosComparacion) ?>;
    var datosDispersion = <?= json_encode($datosDispersion) ?>;
    var datosBarrasApiladas = <?= json_encode($datosBarrasApiladas) ?>;
</script>
<script src="<?= RUTA_JS ?>/graficos.js"></script>

<?php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Gráficos de análisis - CampusCash";

require __DIR__ . '/includes/vistas/plantilla/plantilla.php';
?>