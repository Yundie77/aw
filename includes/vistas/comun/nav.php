<?php
require_once __DIR__ . '/../../../includes/config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
  <div class="navbar-left">
    <a href="<?= RUTA_APP ?>index.php"><img src="<?= RUTA_APP ?>img/logo.png" alt="Logo" class="logo"></a>
    <a href="<?= RUTA_APP ?>index.php">Inicio</a>
    <a href="<?= RUTA_APP ?>gastos.php">Gastos</a>
    <a href="<?= RUTA_APP ?>grupos.php">Grupos</a>
    
    <div class="dropdown">
      <button class="dropdown-btn">Más</button>
      <div class="dropdown-content">
        <a href="<?= RUTA_APP ?>practica1/detalles.php">Detalles</a>
        <a href="<?= RUTA_APP ?>practica1/bocetos.php">Bocetos</a>
        <a href="<?= RUTA_APP ?>practica1/miembros.php">Miembros</a>
        <a href="<?= RUTA_APP ?>practica1/planificacion.php">Planificación</a>
        <a href="<?= RUTA_APP ?>practica1/contacto.php">Contacto</a>
      </div>
    </div>
  </div>
  
  <div class="navbar-right">
    <div class="dropdown">
      <button class="dropdown-btn">Chat</button>
      <div class="dropdown-content">
        <a href="<?= RUTA_APP ?>chat.php?room=chat1">Chat 1</a>
        <a href="<?= RUTA_APP ?>chat.php?room=chat2">Chat 2</a>
        <a href="<?= RUTA_APP ?>chat.php?room=chat3">Chat 3</a>
      </div>
    </div>
    <?php if (isset($_SESSION['user_name'])): ?>
      <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
      <a href="<?= RUTA_APP ?>logout.php">Salir</a>
    <?php else: ?>
      <a href="<?= RUTA_APP ?>login.php">Usuario</a>
    <?php endif; ?>
  </div>
</nav>
