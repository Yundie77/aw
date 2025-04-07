<?php
session_start();
require_once 'includes/config.php';

// verificacion simple de inicio de sesion
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para acceder a esta funcionalidad.";
    header("Location: login.php");
    exit();
}

// inicio del buffer de salida
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

<!-- datos estaticos para las graficas -->
<script>
    // datos para grafico de linea (gastos en el tiempo)
    const lineChartData = {
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        datasets: [{
            label: 'Gastos mensuales',
            data: [650, 590, 800, 810, 560, 550, 480, 650, 820, 900, 750, 820],
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1,
            fill: false
        }]
    };
    
    // datos para grafico comparativo
    const comparisonChartData = {
        labels: ['Alimentación', 'Transporte', 'Material académico', 'Ocio', 'Alojamiento', 'Servicios'],
        datasets: [
            {
                label: 'Tus gastos',
                data: [250, 100, 150, 200, 350, 80],
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            },
            {
                label: 'Promedio estudiantes',
                data: [200, 120, 100, 180, 380, 90],
                backgroundColor: 'rgba(255, 99, 132, 0.7)'
            }
        ]
    };
    
    // datos para grafico de dispersion (ingresos vs gastos)
    const scatterChartData = {
        datasets: [{
            label: 'Relación Ingresos-Gastos por mes',
            data: [
                {x: 900, y: 800, label: 'Enero'},
                {x: 950, y: 780, label: 'Febrero'},
                {x: 900, y: 950, label: 'Marzo'},
                {x: 1000, y: 950, label: 'Abril'},
                {x: 850, y: 700, label: 'Mayo'},
                {x: 950, y: 620, label: 'Junio'},
                {x: 800, y: 700, label: 'Julio'},
                {x: 950, y: 800, label: 'Agosto'},
                {x: 1100, y: 950, label: 'Septiembre'},
                {x: 1050, y: 990, label: 'Octubre'},
                {x: 1000, y: 860, label: 'Noviembre'},
                {x: 1200, y: 1100, label: 'Diciembre'}
            ],
            backgroundColor: 'rgba(255, 99, 132, 0.7)',
            pointRadius: 8,
            pointHoverRadius: 10
        }]
    };
    
    // datos para grafico de barras apiladas
    const stackedChartData = {
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
        datasets: [
            {
                label: 'Alimentación',
                data: [200, 250, 220, 230, 210, 220],
                backgroundColor: 'rgba(255, 99, 132, 0.7)'
            },
            {
                label: 'Transporte',
                data: [100, 90, 110, 90, 95, 100],
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            },
            {
                label: 'Material académico',
                data: [150, 50, 30, 40, 80, 30],
                backgroundColor: 'rgba(255, 206, 86, 0.7)'
            },
            {
                label: 'Ocio',
                data: [80, 120, 150, 100, 95, 130],
                backgroundColor: 'rgba(75, 192, 192, 0.7)'
            },
            {
                label: 'Servicios',
                data: [70, 80, 90, 85, 80, 70],
                backgroundColor: 'rgba(153, 102, 255, 0.7)'
            }
        ]
    };
</script>

<!-- script para generar graficas con chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= RUTA_JS ?>/graficos.js"></script>

<?php
// captura el contenido del buffer
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Gráficos de análisis - CampusCash";

// incluye plantilla principal
require __DIR__ . '/includes/vistas/plantilla/plantilla.php';
?>