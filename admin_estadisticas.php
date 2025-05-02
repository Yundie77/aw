<?php
require_once 'includes/config.php';

use es\ucm\fdi\aw\Aplicacion;

session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    Aplicacion::redirige('login.php');
}

$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();

// 📊 1. Nuevos usuarios por mes
$queryUsuarios = "SELECT DATE_FORMAT(fecha_creacion, '%Y-%m') AS mes, COUNT(*) AS total
                  FROM usuarios GROUP BY mes ORDER BY mes ASC";
$res1 = $conn->query($queryUsuarios);
$datosUsuariosMes = [];
while ($row = $res1->fetch_assoc()) {
    $datosUsuariosMes[] = $row;
}

// 📊 2. Gasto total por categoría (global)
$queryCategorias = "SELECT c.nombre AS categoria, SUM(g.monto) AS total_categoria
                    FROM categorias c JOIN gastos g ON c.id = g.categoria_id
                    GROUP BY c.nombre ORDER BY total_categoria DESC";
$res2 = $conn->query($queryCategorias);
$datosGastoCategorias = [];
$totalGlobal = 0;
while ($row = $res2->fetch_assoc()) {
    $totalGlobal += $row['total_categoria'];
    $datosGastoCategorias[] = $row;
}

$conn->close();

ob_start();
?>

<div class="graficos-container">
    <h1 class="titulo-graficos">Panel de Estadísticas Administrativas</h1>

    <div class="graficos-row">
        <!-- 📈 Gráfico 1: Nuevos usuarios por mes -->
        <div class="grafico-card">
            <h3>Nuevos usuarios por mes</h3>
            <div class="grafico-container">
                <canvas id="usuariosChart"></canvas>
            </div>
            <div class="grafico-descripcion">
                <p>Visualiza la evolución mensual del número de nuevos registros en la plataforma.</p>
            </div>
        </div>

        <!-- 🍩 Gráfico 2: Gasto global por categoría -->
        <div class="grafico-card">
            <h3>Gasto total por categoría</h3>
            <div class="grafico-container">
                <canvas id="donutChart"></canvas>
            </div>
            <div class="grafico-descripcion">
                <p>Distribución total de gastos de todos los usuarios según categoría.</p>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Pasamos los datos PHP a JS
    const datosUsuariosMes = <?= json_encode($datosUsuariosMes) ?>;
    const datosGastoCategorias = <?= json_encode($datosGastoCategorias) ?>;
    const totalGlobal = <?= json_encode($totalGlobal) ?>;
</script>

<script src="<?= RUTA_JS ?>adminEstadisticas.js"></script>

<?php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Panel de Estadísticas";
require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';
?>
