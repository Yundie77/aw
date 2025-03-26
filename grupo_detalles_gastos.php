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
  <title>Detalles del Grupo - Gastos</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- Contenedor principal de detalles -->
  <div class="details-container-gastos">

    <!-- Sección: Uso -->
    <div class="seccion-container">
      <h3 class="highlight">Uso</h3>
      <ul>
        <li>Vuelos: 800$</li>
        <li>Hoteles: 200$</li>
      </ul>
    </div>

    <!-- Sección: Balance -->
    <div class="seccion-container">
      <h3 class="highlight">Balance</h3>
      <ul>
        <li>Pedro: 150$</li>
        <li>Luis: 150$</li>
        <li>Juan: 0$</li>
        <li>Sofía: 75$</li>
        <li>Laura: 75$</li>
        <li>Ana: 0$</li>
        <li>José: 0$</li>
        <li>Miguel: 50$</li>
      </ul>
    </div>

    <!-- Sidebar derecho: Botones de acción (Objetivo, Gastos) -->
    <div class="buttons-sidebar-gastos">
      <a href="grupo_detalles.php"><button>Objetivo</button></a>
      <a href="grupo_detalles_gastos.php"><button class="selected">Gastos</button></a>
    </div>

  </div>

</body>
</html>
