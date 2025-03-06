<?php
session_start();
require 'includes/vistas/comun/header.php';
require 'includes/vistas/comun/nav.php';
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Grupos</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Contenedor de grupos -->
<div class="grupo-list">
  <div class="grupo-item">
    <h2>Viaje Fin de grado</h2>
    <p>(8 participantes)</p>
    <p>Objetivo: 1000$</p>
    <a href="grupo_detalles.php" class="ver-detalles">Ver detalles</a>
  </div>

  <div class="grupo-item">
    <h2>Dublín</h2>
    <p>(3 participantes)</p>
    <p>Objetivo: 500$</p>
    <a href="grupo_detalles.php" class="ver-detalles">Ver detalles</a>
  </div>

  <div class="grupo-item">
    <h2>Ámsterdam</h2>
    <p>(3 participantes)</p>
    <p>Objetivo: 500$</p>
    <a href="grupo_detalles.php" class="ver-detalles">Ver detalles</a>
  </div>
</div>

</body>
</html>
