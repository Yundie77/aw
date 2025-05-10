<?php
require_once 'includes/config.php';
require_once __DIR__ . '/includes/clases/EstadisticasAdmin.php';

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\EstadisticasAdmin;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    Aplicacion::redirige('login.php');
}

$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();
$estadisticas = new EstadisticasAdmin($conn);

// Llamadas a la clase
$datosUsuariosMes = $estadisticas->nuevosUsuariosPorMes();
$datosGastoCategorias = $estadisticas->gastoPorCategoria();
$totalGlobal = array_sum(array_column($datosGastoCategorias, 'total_categoria'));
$barDataEstados = $estadisticas->usuariosPorEstado();
$datosUsuariosRol = $estadisticas->usuariosPorRol();

ob_start();
?>

<div class="graficos-container">
    <h1 class="titulo-graficos">Panel de Estadísticas Administrativas</h1>

    <div class="graficos-row">
        <!-- Gráfico 1 -->
        <div class="grafico-card">
            <h3>Nuevos usuarios por mes</h3>
            <div class="grafico-container"><canvas id="usuariosChart"></canvas></div>
            <div class="grafico-descripcion"><p>Visualiza la evolución mensual del número de nuevos registros en la plataforma.</p></div>
        </div>

        <!-- Gráfico 2 -->
        <div class="grafico-card">
            <h3>Gasto total por categoría</h3>
            <div class="grafico-container"><canvas id="donutChart"></canvas></div>
            <div class="grafico-descripcion"><p>Distribución total de gastos de todos los usuarios según categoría.</p></div>
        </div>

        <!-- Gráfico 3 -->
        <div class="grafico-card">
            <h3>Usuarios activos vs inactivos vs bloqueados</h3>
            <div class="grafico-container"><canvas id="barChartEstados"></canvas></div>
            <div class="grafico-descripcion"><p>Comparativa del número de estados de usuarios en la plataforma.</p></div>
        </div>

        <!-- Gráfico 4 -->
        <div class="grafico-card">
            <h3>Usuarios por rol</h3>
            <div class="grafico-container"><canvas id="usuariosRolChart"></canvas></div>
            <div class="grafico-descripcion"><p>Distribución de los usuarios según su rol asignado (administrador, usuario, etc.).</p></div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const datosUsuariosMes = <?= json_encode($datosUsuariosMes) ?>;
    const datosGastoCategorias = <?= json_encode($datosGastoCategorias) ?>;
    const totalGlobal = <?= json_encode($totalGlobal) ?>;
    const barDataEstados = <?= json_encode($barDataEstados) ?>;
    const datosUsuariosRol = {
        labels: <?= json_encode(array_column($datosUsuariosRol, 'rol')) ?>,
        datos: <?= json_encode(array_column($datosUsuariosRol, 'total')) ?>
    };
</script>
<script src="<?= RUTA_JS ?>adminEstadisticas.js"></script>

<?php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Panel de Estadísticas";
require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';
