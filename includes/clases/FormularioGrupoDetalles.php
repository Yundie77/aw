<?php
namespace es\ucm\fdi\aw;

class FormularioGrupoDetallesGastos {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerNombreGrupo($group_id) {
        $stmt = $this->conn->prepare("SELECT nombre FROM grupos WHERE id = ?");
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['nombre'] : 'Desconocido';
    }

    public function obtenerGastosPorCategoria($group_id) {
        $stmt = $this->conn->prepare("
            SELECT c.nombre AS categoria, SUM(gm.monto) AS total
            FROM gastos_grupales gm 
            INNER JOIN categorias c ON gm.categoria_id = c.id 
            WHERE gm.grupo_id = ? 
            GROUP BY gm.categoria_id
        ");
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerGastosPorParticipante($group_id) {
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

    // Generate the HTML content for the page
    public function generarContenidoGastos($group_id) {
        $usoResults = $this->obtenerGastosPorCategoria($group_id);
        $balanceResults = $this->obtenerGastosPorParticipante($group_id);

        ob_start();
        ?>
        <div class="details-container-gastos">
            <!-- Sección "Uso": gastos por categoría -->
            <div class="seccion-container">
                <h3 class="highlight">Uso</h3>
                <ul>
                    <?php if (count($usoResults) > 0): ?>
                        <?php foreach ($usoResults as $uso): ?>
                            <li>
                                <?php echo htmlspecialchars($uso['categoria']); ?>: 
                                <?php echo htmlspecialchars($uso['total']); ?> €
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No hay datos</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Sección "Balance": gastos por participantes -->
            <div class="seccion-container">
                <h3 class="highlight">Balance</h3>
                <ul>
                    <?php if (count($balanceResults) > 0): ?>
                        <?php foreach ($balanceResults as $balance): ?>
                            <li>
                                <?php echo htmlspecialchars($balance['nombre']); ?>: 
                                <?php echo htmlspecialchars($balance['total']); ?> €
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No hay datos</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Barra lateral con botones -->
            <div class="buttons-sidebar-gastos">
                <a href="grupo_detalles.php?id=<?php echo htmlspecialchars($group_id); ?>">
                    <button>Objetivo</button>
                </a>
                <a href="grupo_detalles_gastos.php?id=<?php echo htmlspecialchars($group_id); ?>">
                    <button class="selected">Gastos</button>
                </a>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}