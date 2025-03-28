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
  <title>Detalles del Grupo</title>
  <link rel="stylesheet" href="css/style.css">
  <!-- Incluimos Chart.js desde CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <!-- Contenedor principal de detalles -->
  <div class="details-container">
    
    <!-- Sidebar izquierdo: Nombre del grupo + participantes -->
    <div class="sidebar-container">
      <div class="details-header">
          <h2>Viaje Fin de grado</h2>
      </div>
      <div class="details-sidebar">
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
    </div>

    <!-- Sección central: Gráfico circular -->
    <div class="chart-container">
      <canvas id="detallesChart"></canvas>
    </div>

    <!-- Sidebar derecho: Botones de acción (Objetivo, Gastos) -->
    <div class="buttons-sidebar">
      <a href="grupo_detalles.php"><button class="selected">Objetivo</button></a>
      <a href="grupo_detalles_gastos.php"><button>Gastos</button></a>
    </div>

  </div>
  <script src="js/detallesChart.js"></script>
</body>
</html>