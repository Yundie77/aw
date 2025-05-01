<?php
// Iniciamos la sesión
session_start();
require_once __DIR__ . '/includes/config.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: " . RUTA_APP . "login.php");
    exit();
}

ob_start();
?>

<div class="admin-container">
  <h2>Administrador</h2>

  <div class="search-box">
    <h3>Buscar usuarios</h3>
    <input type="text" id="buscarUsuario" placeholder="Buscar por nombre, email..." />
  </div>

  <div id="usuarios-container">Cargando usuarios...</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?= RUTA_JS ?>adminUsuarios.js"></script>

<?php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Gestión de Usuarios";
require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';
?>
