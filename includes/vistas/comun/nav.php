<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once RAIZ_APP . '/clases/Grupos.php';  

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$esAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
$estaLogueado = isset($_SESSION['user_id']);
$gruposUsuario = [];

if ($estaLogueado && !$esAdmin) {
    $app = \es\ucm\fdi\aw\Aplicacion::getInstance();
    $conn = $app->getConexionBd();
    $manejadorGrupos = new Grupos($conn);
    $gruposUsuario = $manejadorGrupos->obtenerGruposPorUsuarioId($_SESSION['user_id']);
}
?>
<nav>
  <div class="navbar-left">
    <a href="<?= RUTA_APP ?>index.php"><img src="<?= RUTA_APP ?>img/logo.png" alt="Logo" class="logo"></a>
    <a href="<?= RUTA_APP ?>index.php">Inicio</a>

    <?php if ($estaLogueado && !$esAdmin): ?>
      <a href="<?= RUTA_APP ?>gastos.php">Gastos</a>
      <a href="<?= RUTA_APP ?>grupos.php">Grupos</a>
      <a href="<?= RUTA_APP ?>graficos.php">Gráficos</a>
    <?php elseif ($estaLogueado && $esAdmin): ?>
      <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
  <div class="dropdown">
    <button class="dropdown-btn">Admin</button>
    <div class="dropdown-content">
      <a href="/aw/admin.php">Usuarios</a>
      <a href="/aw/admin_estadisticas.php">Estadísticas</a>
      <a href="/aw/admin_configuracion.php">Configuración</a>
    </div>
  </div>
<?php endif; ?>

    <?php endif; ?>
  </div>

  <div class="navbar-right">
    <?php if ($estaLogueado && !$esAdmin): ?>
      <div class="dropdown chat-dropdown-container">
        <button class="dropdown-btn">Chat</button>
        <div class="dropdown-content">
          <div class="chat-group-list">
            <?php if (!empty($gruposUsuario)): ?>
              <?php foreach ($gruposUsuario as $grupo): ?>
                <a href="#" class="chat-group-item" data-group-id="<?= htmlspecialchars($grupo['id']) ?>" data-group-name="<?= htmlspecialchars($grupo['nombre']) ?>">
                  <?= htmlspecialchars($grupo['nombre']) ?>
                </a>
              <?php endforeach; ?>
            <?php else: ?>
              <span>No participas en ningún grupo.</span>
            <?php endif; ?>
          </div>
          <div class="chat-interface-container" style="display:none;">
            <div class="chat-header">
              <span class="chat-group-name-display"></span>
              <button class="chat-back-to-groups">&lt; Grupos</button>
              <button class="chat-close-btn" title="Cerrar chat">&times;</button>
            </div>
            <div class="chat-messages-area">
            </div>
            <div class="chat-input-area">
              <textarea class="chat-message-input" placeholder="Escribe un mensaje..."></textarea>
              <button class="chat-send-btn">Enviar</button>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_name'])): ?>
      <span class="username-display"><?= htmlspecialchars($_SESSION['user_name']); ?></span>
      <a href="<?= RUTA_APP ?>logout.php">Salir</a>
    <?php else: ?>
      <a href="<?= RUTA_APP ?>login.php">Usuario</a>
    <?php endif; ?>
  </div>
</nav>