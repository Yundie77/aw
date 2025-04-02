<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para acceder a esta funcionalidad.";
    header("Location: login.php");
    exit();
}
require 'includes/vistas/comun/header.php';
require 'includes/vistas/comun/nav.php';
require_once 'includes/config.php';

// Actualizamos la query para calcular el número real de participantes para cada grupo
$result = $conn->query("SELECT g.*, (SELECT COUNT(*) FROM grupo_usuarios WHERE grupo_id = g.id) AS participantes FROM grupos g");
if (!$result) {
    die("Query error: " . $conn->error);
}
$grupos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Grupos</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/grupos.css">
</head>
<body>
    <div class="grupo-list">
        <?php if(count($grupos) > 0): ?>
            <?php foreach($grupos as $grupo): ?>
            <div class="grupo-item">
                <h2><?php echo htmlspecialchars($grupo['nombre']); ?></h2>
                <p>(<?php echo htmlspecialchars($grupo['participantes']); ?> participantes)</p>
                <p>Objetivo: <?php echo htmlspecialchars($grupo['objetivo']); ?>$</p>
                <a href="grupo_detalles.php?id=<?php echo htmlspecialchars($grupo['id']); ?>" class="ver-detalles">Ver detalles</a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-grupos">
                <p>No hay grupos disponibles</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Bloque de acciones para interactuar con la base de datos -->
    <div class="grupo-actions">
        <button onclick="openModal('modal-agregar-grupo')">Agregar nuevo grupo</button>
        <button onclick="openModal('modal-agregar-miembro')">Agregar nuevo miembro al grupo</button>
        <button onclick="openModal('modal-modificar-grupo')">Modificar grupo</button>
        <button onclick="openModal('modal-eliminar-grupo')">Eliminar grupo</button>
    </div>
    
    <!-- Modal: Agregar nuevo grupo -->
    <div id="modal-agregar-grupo" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal('modal-agregar-grupo')">&times;</span>
        <h2>Agregar Nuevo Grupo</h2>
        <form action="agregar_grupo.php" method="post">
            <label for="nombre">Nombre del Grupo:</label>
            <input type="text" id="nombre" name="nombre" required>
            <br>
            <label for="objetivo">Objetivo (€):</label>
            <input type="number" step="1" id="objetivo" name="objetivo" required>
            <br>
            <button type="submit">Agregar Grupo</button>
        </form>
      </div>
    </div>
    
    <!-- Modal: Agregar nuevo miembro al grupo -->
    <div id="modal-agregar-miembro" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal('modal-agregar-miembro')">&times;</span>
        <h2>Agregar Nuevo Miembro al Grupo</h2>
        <form action="agregar_miembro.php" method="post">
            <label for="grupo_id">Seleccione el Grupo:</label>
            <select name="grupo_id" id="grupo_id" required>
                <?php
                $gruposResult = $conn->query("SELECT id, nombre FROM grupos");
                while($grupo = $gruposResult->fetch_assoc()):
                ?>
                <option value="<?php echo $grupo['id']; ?>"><?php echo htmlspecialchars($grupo['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <label for="usuario_id">Seleccione el Miembro:</label>
            <select name="usuario_id" id="usuario_id" required>
                <?php
                $usuariosResult = $conn->query("SELECT id, nombre FROM usuarios");
                while($usuario = $usuariosResult->fetch_assoc()):
                ?>
                <option value="<?php echo $usuario['id']; ?>"><?php echo htmlspecialchars($usuario['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <button type="submit">Agregar Miembro</button>
        </form>
      </div>
    </div>
    
    <!-- Modal: Modificar grupo -->
    <div id="modal-modificar-grupo" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal('modal-modificar-grupo')">&times;</span>
        <h2>Modificar Grupo</h2>
        <form action="modificar_grupo.php" method="post">
            <label for="grupo_id_mod">Seleccione el Grupo a Modificar:</label>
            <select name="grupo_id" id="grupo_id_mod" required>
                <?php
                $gruposResult = $conn->query("SELECT id, nombre FROM grupos");
                while($grupo = $gruposResult->fetch_assoc()):
                ?>
                <option value="<?php echo $grupo['id']; ?>"><?php echo htmlspecialchars($grupo['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <label for="nombre_mod">Nuevo Nombre:</label>
            <input type="text" id="nombre_mod" name="nombre" required>
            <br>
            <label for="objetivo_mod">Nuevo Objetivo (€):</label>
            <input type="number" step="1" id="objetivo_mod" name="objetivo" required>
            <br>
            <button type="submit">Modificar Grupo</button>
        </form>
      </div>
    </div>
    
    <!-- Modal: Eliminar grupo -->
    <div id="modal-eliminar-grupo" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal('modal-eliminar-grupo')">&times;</span>
        <h2>Eliminar Grupo</h2>
        <form action="eliminar_grupo.php" method="post" onsubmit="return confirm('¿Está seguro de que desea eliminar el grupo seleccionado?');">
            <label for="grupo_id_del">Seleccione el Grupo a Eliminar:</label>
            <select name="grupo_id" id="grupo_id_del" required>
                <?php
                $gruposResult = $conn->query("SELECT id, nombre FROM grupos");
                while($grupo = $gruposResult->fetch_assoc()):
                ?>
                <option value="<?php echo $grupo['id']; ?>"><?php echo htmlspecialchars($grupo['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <button type="submit">Eliminar Grupo</button>
        </form>
      </div>
    </div>

    <script src="js/modal.js"></script>
    <?php require 'includes/vistas/comun/footer.php'; ?>
</body>
</html>