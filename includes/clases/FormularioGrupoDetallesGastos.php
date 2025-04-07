<?php
namespace es\ucm\fdi\aw;

class FormularioGrupoDetallesGastos {
    private $conn;
    private $grupos;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->grupos = new \Grupos($conn);
    }

    // Llama al método de Grupos
    public function obtenerNombreGrupo($group_id) {
        $grupo = $this->grupos->obtenerGrupo($group_id); 
        return $grupo ? $grupo['nombre'] : 'Desconocido';
    }

    // Llama al método de Grupos
    public function obtenerGastosPorCategoria($group_id) {
        return $this->grupos->obtenerGastosPorCategoria($group_id); 
    }

    // Llama al método de Grupos
    public function obtenerGastosPorParticipante($group_id) {
        return $this->grupos->obtenerGastosPorParticipante($group_id); 
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