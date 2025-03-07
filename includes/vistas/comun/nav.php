<?php
// Barra de navegación común
?>

<nav>
  <div class="navbar-left">
    <a href="/aw/index.php"><img src="img/logo.png" alt="Logo" class="logo"></a>
    <a href="/aw/index.php">Inicio</a>
    <a href="/aw/gastos.php">Gastos</a>
    <a href="/aw/grupos.php">Grupos</a>
    <!-- Dropdown para enlaces legacy -->
    <div class="dropdown">
      <button class="dropdown-btn">Más</button>
      <div class="dropdown-content">
        <a href="/aw/detalles.php">Detalles</a>
        <a href="/aw/bocetos.php">Bocetos</a>
        <a href="/aw/miembros.php">Miembros</a>
        <a href="/aw/planificacion.php">Planificación</a>
        <a href="/aw/contacto.php">Contacto</a>
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
    <a href="/aw/login.php">Usuario</a>
  </div>
</nav>
