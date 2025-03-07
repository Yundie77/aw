<?php
// Barra de navegación común
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
  <div class="navbar-left">
    <a href="/aw/index.php"><img src="/aw/img/logo.png" alt="Logo" class="logo"></a>
    <a href="/aw/index.php">Inicio</a>
    <a href="/aw/gastos.php">Gastos</a>
    <a href="/aw/grupos.php">Grupos</a>
    
    <!-- Dropdown para enlaces legacy -->
    <div class="dropdown">
      <button class="dropdown-btn">Más</button>
      <div class="dropdown-content">
        <a href="/aw/includes/practica1/detalles.php">Detalles</a>
        <a href="/aw/includes/practica1/bocetos.php">Bocetos</a>
        <a href="/aw/includes/practica1/miembros.php">Miembros</a>
        <a href="/aw/includes/practica1/planificacion.php">Planificación</a>
        <a href="/aw/includes/practica1/contacto.php">Contacto</a>
      </div>
    </div>
  </div>
  
  <div class="navbar-right">
    <!-- Chat dropdown para seleccionar sala de chat -->
    <div class="dropdown">
      <button class="dropdown-btn">Chat</button>
      <div class="dropdown-content">
        <a href="/aw/chat.php?room=chat1">Chat 1</a>
        <a href="/aw/chat.php?room=chat2">Chat 2</a>
        <a href="/aw/chat.php?room=chat3">Chat 3</a>
      </div>
    </div>
    <?php if (isset($_SESSION['user_name'])): ?>
      <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
      <a href="/aw/logout.php">Salir</a>
    <?php else: ?>
      <a href="/aw/login.php">Usuario</a>
    <?php endif; ?>
  </div>
</nav>
