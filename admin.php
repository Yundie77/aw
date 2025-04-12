<?php
// Iniciamos la sesión
session_start();

// Mostrar mensaje de error si está en la sesión
if (isset($_SESSION['mensaje_error'])) {
    echo '<div class="mensaje-error">' . $_SESSION['mensaje_error'] . '</div>';
    unset($_SESSION['mensaje_error']); // Limpiar el mensaje después de mostrarlo
}

// Mostrar mensaje de éxito, si existe
if (isset($_SESSION['mensaje_exito'])) {
    echo '<div class="mensaje-exito">' . $_SESSION['mensaje_exito'] . '</div>';
    unset($_SESSION['mensaje_exito']); // Limpiar el mensaje después de mostrarlo
}

// Incluimos la configuración y la clase Admin
require_once 'includes/config.php';
require_once 'includes/clases/Admin.php';

// Obtenemos la instancia de la aplicación y la conexión a la base de datos
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para acceder a esta funcionalidad.";
    header("Location: login.php");
    exit();
}

// Creamos una instancia de la clase Admin
$admin = new \es\ucm\fdi\aw\Admin($conn);

// Iniciamos el buffer de salida
ob_start();
?>
<div class="admin-container">
    <h2>Administrador</h2>
    <div class="search-box">
        <h3>Buscar usuarios</h3>
        <form method="GET" action="admin.php">
            <input type="text" name="search" placeholder="Buscar por nombre, email..." />
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
                <th>Activo/Inactivo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#001</td>
                <td>Pedro</td>
                <td>pedgen@ucm.es</td>
                <td>Admin</td>
                <td>Activo</td>
                <td>
                    <button>Cambiar Rol</button>
                </td>
            </tr>
            <tr>
                <td>#002</td>
                <td>Luis</td>
                <td>lui@ucm.es</td>
                <td>Usuario</td>
                <td>Inactivo</td>
                <td>
                    <button>Cambiar Rol</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php
// Capturamos el contenido generado en el buffer
$contenidoPrincipal = ob_get_clean();

// Título de la página
$tituloPagina = "Página de administrador";

// Incluimos la plantilla principal
require __DIR__ . '/includes/vistas/plantilla/plantilla.php';