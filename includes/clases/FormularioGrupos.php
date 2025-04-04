<?php
namespace es\ucm\fdi\aw;

class FormularioGrupos {
    private $conn;

    // Constructor para inicializar la conexión a la base de datos
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Método para obtener los datos de los grupos desde la base de datos
    public function obtenerGrupos() {
        $query = "SELECT g.*, (SELECT COUNT(*) FROM grupo_usuarios WHERE grupo_id = g.id) AS participantes FROM grupos g";
        $result = $this->conn->query($query);
        if (!$result) {
            die("Error en la consulta: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Método para generar el HTML de la lista de grupos
    public function generarListaGrupos() {
        $grupos = $this->obtenerGrupos();
        ob_start();
        ?>
        <div class="grupo-list">
            <?php if (count($grupos) > 0): ?>
                <?php foreach ($grupos as $grupo): ?>
                    <div class="grupo-item">
                        <h2><?php echo htmlspecialchars($grupo['nombre']); ?></h2>
                        <p>(<?php echo htmlspecialchars($grupo['participantes']); ?> participantes)</p>
                        <p>Objetivo: <?php echo htmlspecialchars($grupo['objetivo']); ?> €</p>
                        <a href="grupo_detalles.php?id=<?php echo htmlspecialchars($grupo['id']); ?>" class="ver-detalles">Ver detalles</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-grupos">
                    <p>No hay grupos disponibles</p>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    // Método para generar el HTML de los botones para las ventanas modales
    public function generarBotones() {
        ob_start();
        ?>
        <div class="grupo-actions">
            <button onclick="openModal('modal-agregar-grupo')">Agregar nuevo grupo</button>
            <button onclick="openModal('modal-agregar-miembro')">Agregar nuevo miembro al grupo</button>
            <button onclick="openModal('modal-modificar-grupo')">Modificar grupo</button>
            <button onclick="openModal('modal-eliminar-grupo')">Eliminar grupo</button>
        </div>
        <?php
        return ob_get_clean();
    }

    // Método para generar el HTML de las ventanas modales
    public function generarModales() {
        ob_start();
        ?>
        <!-- Modal: Agregar nuevo grupo -->
        <div id="modal-agregar-grupo" class="modal">
            <div class="modal-content-grupo">
                <span class="close" onclick="closeModal('modal-agregar-grupo')">&times;</span>
                <h2>Agregar Nuevo Grupo</h2>
                <form action="formulario_modal.php?accion=agregar_grupo" method="post">
                    <label for="nombre">Nombre del Grupo:</label>
                    <input type="text" id="nombre" name="nombre" required>
                    <label for="objetivo">Objetivo (€):</label>
                    <input type="number" step="1" id="objetivo" name="objetivo" required>
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required></textarea>
                    <button type="submit">Agregar Grupo</button>
                </form>
            </div>
        </div>

        <!-- Modal: Agregar nuevo miembro -->
        <div id="modal-agregar-miembro" class="modal">
            <div class="modal-content-grupo">
                <span class="close" onclick="closeModal('modal-agregar-miembro')">&times;</span>
                <h2>Agregar Nuevo Miembro</h2>
                <form action="formulario_modal.php?accion=agregar_miembro" method="post">
                    <label for="grupo_id">Seleccione el Grupo:</label>
                    <select name="grupo_id" id="grupo_id" required>
                        <?php foreach ($this->obtenerGrupos() as $grupo): ?>
                            <option value="<?php echo htmlspecialchars($grupo['id']); ?>"><?php echo htmlspecialchars($grupo['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="usuario_id">Seleccione el Miembro:</label>
                    <input type="text" id="usuario_id" name="usuario_id" required>
                    <label for="monto">Monto (€):</label>
                    <input type="number" step="1" id="monto" name="monto" required>
                    <button type="submit">Agregar Miembro</button>
                </form>
            </div>
        </div>

        <!-- Modal: Modificar grupo -->
        <div id="modal-modificar-grupo" class="modal">
            <div class="modal-content-grupo">
                <span class="close" onclick="closeModal('modal-modificar-grupo')">&times;</span>
                <h2>Modificar Grupo</h2>
                <form action="formulario_modal.php?accion=modificar_grupo" method="post">
                    <label for="grupo_id_mod">Seleccione el Grupo:</label>
                    <select name="grupo_id" id="grupo_id_mod" required>
                        <?php foreach ($this->obtenerGrupos() as $grupo): ?>
                            <option value="<?php echo htmlspecialchars($grupo['id']); ?>"><?php echo htmlspecialchars($grupo['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="nombre_mod">Nuevo Nombre:</label>
                    <input type="text" id="nombre_mod" name="nombre" required>
                    <label for="objetivo_mod">Nuevo Objetivo (€):</label>
                    <input type="number" step="1" id="objetivo_mod" name="objetivo" required>
                    <label for="descripcion_mod">Nueva Descripción:</label>
                    <textarea id="descripcion_mod" name="descripcion" required></textarea>
                    <button type="submit">Modificar Grupo</button>
                </form>
            </div>
        </div>

        <!-- Modal: Eliminar grupo -->
        <div id="modal-eliminar-grupo" class="modal">
            <div class="modal-content-grupo">
                <span class="close" onclick="closeModal('modal-eliminar-grupo')">&times;</span>
                <h2>Eliminar Grupo</h2>
                <form action="formulario_modal.php?accion=eliminar_grupo" method="post">
                    <label for="grupo_id_del">Seleccione el Grupo:</label>
                    <select name="grupo_id" id="grupo_id_del" required>
                        <?php foreach ($this->obtenerGrupos() as $grupo): ?>
                            <option value="<?php echo htmlspecialchars($grupo['id']); ?>"><?php echo htmlspecialchars($grupo['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Eliminar Grupo</button>
                </form>
            </div>
            <script src="js/modal.js"></script>
        </div>
        <?php
        return ob_get_clean();
    }
}