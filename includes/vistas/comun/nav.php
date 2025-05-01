<?php
require_once __DIR__ . '/../../../includes/config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
?>
<nav>
  <div class="navbar-left">
    <a href="<?= RUTA_APP ?>index.php"><img src="<?= RUTA_APP ?>img/logo.png" alt="Logo" class="logo"></a>
    <a href="<?= RUTA_APP ?>index.php">Inicio</a>

    <?php if (!$isAdmin): ?>
      <a href="<?= RUTA_APP ?>gastos.php">Gastos</a>
      <a href="<?= RUTA_APP ?>grupos.php">Grupos</a>
      <a href="<?= RUTA_APP ?>graficos.php">Gráficos</a>
    <?php else: ?>
      <div class="dropdown">
        <button class="dropdown-btn">Admin</button>
        <div class="dropdown-content">
          <a href="<?= RUTA_APP ?>admin.php">Usuarios</a>
          <a href="<?= RUTA_APP ?>admin_estadisticas.php">Estadísticas</a>
          <a href="<?= RUTA_APP ?>admin_configuracion.php">Configuración</a>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <div class="navbar-right">
    <?php if (!$isAdmin): ?>
      <div class="dropdown">
        <button class="dropdown-btn">Chat</button>
        <div class="dropdown-content">
          <a href="<?= RUTA_APP ?>chat.php?room=chat1">Chat 1</a>
          <a href="<?= RUTA_APP ?>chat.php?room=chat2">Chat 2</a>
          <a href="<?= RUTA_APP ?>chat.php?room=chat3">Chat 3</a>
        </div>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_name'])): ?>
      <span><?= htmlspecialchars($_SESSION['user_name']); ?></span>
      <a href="<?= RUTA_APP ?>logout.php">Salir</a>
    <?php else: ?>
      <a href="<?= RUTA_APP ?>login.php">Usuario</a>
    <?php endif; ?>
  </div>
</nav>
