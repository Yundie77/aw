<?php
namespace es\ucm\fdi\aw;

class FormularioGrupoDetallesGastos {
    private $conn;
    private $grupos;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->grupos = new \Grupos($conn); // Instancia de la clase Grupos
    }

    // Obtener los detalles de un grupo por ID
    public function obtenerGrupo($group_id) {
        return $this->grupos->obtenerGrupo($group_id); // Llama al método de Grupos
    }

    // Obtener los participantes y sus gastos
    public function obtenerParticipantes($group_id) {
        return $this->grupos->obtenerParticipantes($group_id); // Llama al método de Grupos
    }

    // Generar el gráfico de participantes
    public function generarGrafico($participants) {
        $chartLabels = json_encode(array_column($participants, 'nombre'));
        $chartData   = json_encode(array_column($participants, 'total'));
        return [$chartLabels, $chartData];
    }

    // Generar el HTML completo para la página de detalles del grupo
    public function generarContenidoDetalles($group_id) {
        $grupo = $this->obtenerGrupo($group_id);
        if (!$grupo) {
            return "<p>Grupo no encontrado.</p>";
        }
        $participantes = $this->obtenerParticipantes($group_id);
        list($chartLabels, $chartData) = $this->generarGrafico($participantes);

        ob_start();
        ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- Contenedor principal de detalles del grupo -->
        <div class="details-container">
            <!-- Barra lateral izquierda: nombre del grupo y lista de participantes -->
            <div class="sidebar-container">
                <div class="details-header">
                    <h2><?php echo htmlspecialchars($grupo['nombre']); ?></h2>
                </div>
                <div class="details-sidebar">
                    <ul>
                        <?php foreach ($participantes as $p): ?>
                            <li><?php echo htmlspecialchars($p['nombre']); ?>: <?php echo htmlspecialchars($p['total']); ?> €</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Bloque central: gráfico -->
            <div class="chart-container">
                <canvas id="detallesChart"></canvas>
            </div>

            <!-- Barra lateral derecha: botones para cambiar entre "Objetivo" y "Gastos" -->
            <div class="buttons-sidebar">
                <a href="grupo_detalles.php?id=<?php echo $group_id; ?>"><button class="selected">Objetivo</button></a>
                <a href="grupo_detalles_gastos.php?id=<?php echo $group_id; ?>"><button>Gastos</button></a>
            </div>
        </div>

        <!-- Enviamos los datos para construir el gráfico a JavaScript -->
        <script>
            const chartLabels = <?php echo $chartLabels; ?>;
            const chartData = <?php echo $chartData; ?>;
            console.log("chartLabels:", chartLabels, "chartData:", chartData);
        </script>

        <!-- Conectamos el script externo para dibujar el gráfico -->
        <script src="<?= RUTA_JS ?>detallesChart.js"></script>
        <?php
        return ob_get_clean();
    }
}