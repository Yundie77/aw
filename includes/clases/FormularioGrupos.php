<?php

namespace es\ucm\fdi\aw;

class FormularioGrupos
{
    private $conn;

    // Constructor para inicializar la conexión a la base de datos
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Método para obtener los datos de los grupos desde la base de datos
    public function obtenerGrupos()
    {
        $usuario_id = $_SESSION['user_id'];

        $query = "SELECT g.*, (SELECT COUNT(*) FROM grupo_usuarios WHERE grupo_id = g.id) AS participantes 
        FROM grupos g
        INNER JOIN grupo_usuarios gu ON g.id = gu.grupo_id
        WHERE gu.usuario_id = ? ";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conn->error);
        }

        // Vincula el ID del usuario a la consulta
        $stmt->bind_param("i", $usuario_id);

        // Ejecuta la consulta
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("Error en la consulta: " . $this->conn->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Nuevo método para obtener los usuarios desde la base de datos
    public function obtenerUsuarios()
    {
        $query = "SELECT id, nombre FROM usuarios"; // Ajusta 'nombre' al campo real de tu tabla de usuarios
        $result = $this->conn->query($query);
        if (!$result) {
            die("Error en la consulta de usuarios: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Método para generar el HTML de la lista de grupos
    public function generarListaGrupos()
    {
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

    public function obtenerGruposComoAdmin($userId)
    {
        $query = "SELECT g.id, g.nombre 
                  FROM grupos g
                  JOIN grupo_usuarios gu ON g.id = gu.grupo_id
                  WHERE gu.usuario_id = ? AND gu.rol_grupo = 'admin_grupo'";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $grupos = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $grupos;
    }

    //usuarios que no estan ya en el grupo
    public function obtenerUsuariosDisponibles($grupoId)
    {
        $query = "SELECT u.id, u.nombre 
                  FROM usuarios u
                  WHERE u.id NOT IN (SELECT gu.usuario_id FROM grupo_usuarios gu WHERE gu.grupo_id = ?)";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $grupoId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $usuarios;
    }

    // Método para generar el HTML de los botones para las ventanas modales
    public function generarBotones()
    {
        ob_start();
    ?>
        <div class="grupo-actions">
            <button onclick="openModal('modal-agregar-grupo')">Agregar nuevo grupo</button>
            <button onclick="openModal('modal-agregar-miembro')">Agregar nuevo miembro al grupo</button>
            <button onclick="openModal('modal-modificar-grupo')">Modificar grupo</button>
            <button onclick="openModal('modal-eliminar-grupo')">Eliminar grupo</button>
            <button onclick="openModal('modal-salir-grupo')">Salir grupo</button>
        </div>
    <?php
        return ob_get_clean();
    }

    // Método para generar el HTML de las ventanas modales
    public function generarModales()
    {
        $usuarios = $this->obtenerUsuarios();
        ob_start();
    ?>
        <!-- Modal: Agregar nuevo grupo (permitido para cualquier usuario) -->
        <div id="modal-agregar-grupo" class="modal">
            <div class="modal-content-grupo">
                <span class="close" onclick="closeModal('modal-agregar-grupo')">&times;</span>
                <h2>Agregar Nuevo Grupo</h2>
                <form data-ajax="true" action="agregar_grupo.php" method="POST">
                    <div class="form-row">
                        <label for="nombre">Nombre del Grupo:</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-row">
                        <label for="objetivo">Objetivo (€):</label>
                        <input type="number" step="1" id="objetivo" name="objetivo" required>
                    </div>
                    <div class="form-row">
                        <button type="submit">Agregar Grupo</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal: Agregar nuevo miembro -->
        <div id="modal-agregar-miembro" class="modal">
            <div class="modal-content-grupo">
                <span class="close" onclick="closeModal('modal-agregar-miembro')">&times;</span>
                <h2>Agregar Nuevo Miembro</h2>
                <form data-ajax="true" action="agregar_miembro.php" method="POST">
                    <div class="form-row">
                        <label for="grupo_id">Seleccione el Grupo:</label>
                        <select name="grupo_id" id="grupo_id" required>
                            <?php foreach ($this->obtenerGrupos() as $grupo): ?>
                                <option value="<?php echo htmlspecialchars($grupo['id']); ?>">
                                    <?php echo htmlspecialchars($grupo['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="usuario_id">Seleccione el Miembro:</label>
                        <select name="usuario_id" id="usuario_id" required>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?php echo htmlspecialchars($usuario['id']); ?>">
                                    <?php echo htmlspecialchars($usuario['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="rol_grupo">Rol del miembro:</label>
                        <select name="rol_grupo" id="rol_grupo" required>
                                <option value="miembro">Miembro</option>
                                <option value="admin_grupo">Administardor del grupo</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <button type="submit">Agregar Miembro</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal: Modificar grupo -->
        <div id="modal-modificar-grupo" class="modal">
            <div class="modal-content-grupo">
                <span class="close" onclick="closeModal('modal-modificar-grupo')">&times;</span>
                <h2>Modificar Grupo</h2>
                <form data-ajax="true" action="modificar_grupo.php" method="POST">
                    <div class="form-row">
                        <label for="grupo_id_mod">Seleccione el Grupo:</label>
                        <select name="grupo_id" id="grupo_id_mod" required>
                            <?php
                            $userId = $_SESSION['user_id'];
                            foreach ($this->obtenerGruposComoAdmin($userId) as $grupo):
                            ?>
                                <option value="<?php echo htmlspecialchars($grupo['id']); ?>">
                                    <?php echo htmlspecialchars($grupo['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="nombre_mod">Nuevo Nombre:</label>
                        <input type="text" id="nombre_mod" name="nombre" required>
                    </div>
                    <div class="form-row">
                        <label for="objetivo_mod">Nuevo Objetivo (€):</label>
                        <input type="number" step="1" id="objetivo_mod" name="objetivo" required>
                    </div>
                    <div class="form-row">
                        <button type="submit">Modificar Grupo</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal: Eliminar grupo -->
        <div id="modal-eliminar-grupo" class="modal">
            <div class="modal-content-grupo">
                <span class="close" onclick="closeModal('modal-eliminar-grupo')">&times;</span>
                <h2>Eliminar Grupo</h2>
                <form data-ajax="true" action="eliminar_grupo.php" method="POST">
                    <div class="form-row">
                        <label for="grupo_id_del">Seleccione el Grupo:</label>
                        <select name="grupo_id" id="grupo_id_del" required>
                            <?php
                            $userId = $_SESSION['user_id'];
                            foreach ($this->obtenerGruposComoAdmin($userId) as $grupo):
                            ?>
                                <option value="<?php echo htmlspecialchars($grupo['id']); ?>">
                                    <?php echo htmlspecialchars($grupo['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-row">
                        <button type="submit" style="background-color: #e74c3c; color: white;">Eliminar Grupo</button>
                    </div>
                </form>
            </div>
        </div>
       
        <!-- Modal: Salir grupo -->
    <div id="modal-salir-grupo" class="modal">
    <div class="modal-content-grupo">
        <span class="close" onclick="closeModal('modal-salir-grupo')">&times;</span>
        <h2>Salir Grupo</h2>
        <form data-ajax="true" action="salir_grupo.php" method="POST">
        <div class="form-row">
            <label for="grupo_id_salir">Seleccione el Grupo:</label>
            <select name="grupo_id" id="grupo_id_salir" required>
            <?php
                    $userId = $_SESSION['user_id'];
                        foreach ($this->obtenerGrupos() as $grupo):
                        ?>
                            <option value="<?= htmlspecialchars($grupo['id']) ?>">
                                        <?= htmlspecialchars($grupo['nombre']) ?>
                             </option>
                         <?php endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <button type="submit" style="background-color: #e74c3c; color: white;">Salir Grupo</button>
        </div>
        </form>
    </div>
    </div>


        <div id="mensaje-resultado"></div>
<?php
        return ob_get_clean();
    }
}
