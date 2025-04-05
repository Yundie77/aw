<?php
// Barra de navegación común
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
  <div class="navbar-left">
    <a href="./index.php"><img src="./img/logo.png" alt="Logo" class="logo"></a>
    <a href="./index.php">Inicio</a>
    <a href="./gastos.php">Gastos</a>
    <a href="./grupos.php">Grupos</a>
    
    <!-- Dropdown para enlaces legacy -->
    <div class="dropdown">
      <button class="dropdown-btn">Más</button>
      <div class="dropdown-content">
        <a href="./practica1/detalles.php">Detalles</a>
        <a href="./practica1/bocetos.php">Bocetos</a>
        <a href="./practica1/miembros.php">Miembros</a>
        <a href="./practica1/planificacion.php">Planificación</a>
        <a href="./practica1/contacto.php">Contacto</a>
      </div>
    </div>
  </div>
  
  <div class="navbar-right">
    <!-- Chat dropdown para seleccionar sala de chat -->
    <div class="dropdown">
      <button class="dropdown-btn">Chat</button>
      <div class="dropdown-content">
        <a href="./chat.php?room=chat1">Chat 1</a>
        <a href="./chat.php?room=chat2">Chat 2</a>
        <a href="./chat.php?room=chat3">Chat 3</a>
      </div>
    </div>
    <?php if (isset($_SESSION['user_name'])): ?>
      <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
      <a href="./logout.php">Salir</a>
    <?php else: ?>
      <a href="./login.php">Usuario</a>
    <?php endif; ?>
  </div>
</nav>
