<?php
namespace es\ucm\fdi\aw;

class FormularioGrupoDetallesGastos {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener los detalles de un grupo por ID
    public function obtenerGrupo($group_id) {
        $stmt = $this->conn->prepare("SELECT * FROM grupos WHERE id = ?");
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener los participantes y sus gastos
    public function obtenerParticipantes($group_id) {
        $stmt = $this->conn->prepare("
            SELECT u.nombre, COALESCE(SUM(gm.monto), 0) AS total 
            FROM grupo_usuarios gu 
            INNER JOIN usuarios u ON gu.usuario_id = u.id 
            LEFT JOIN gastos_grupales gm ON gu.grupo_id = gm.grupo_id AND gu.usuario_id = gm.usuario_id 
            WHERE gu.grupo_id = ? 
            GROUP BY gu.usuario_id, u.nombre
        ");
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
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
        <script src="js/detallesChart.js"></script>
        <?php
        return ob_get_clean();
    }
}