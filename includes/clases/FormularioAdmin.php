<?php
session_start();
require_once 'includes/config.php';
use es\ucm\fdi\aw\Admin;

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();
$admin = new Admin($conn);

$criterio = $_GET['search'] ?? '';
$usuarios = $criterio ? $admin->buscarUsuarios($criterio) : $admin->obtenerTodosUsuarios();

ob_start();
?>
<div class="admin-container">
    <h2>Panel de Administración</h2>
    <div class="search-box">
        <h3>Buscar usuarios</h3>
        <form method="GET" action="formularioadmin.php">
            <input type="text" name="search" placeholder="Buscar por nombre, email..." value="<?= htmlspecialchars($criterio) ?>">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <table class="user-table">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['id']) ?></td>
                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= htmlspecialchars($usuario['rol']) ?></td>
                    <td>
                        <form method="POST" action="cambiar_rol.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                            <select name="rol">
                                <option value="usuario" <?= $usuario['rol'] === 'usuario' ? 'selected' : '' ?>>Usuario</option>
                                <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <button type="submit">Cambiar Rol</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Formulario Admin";

require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';
?>
