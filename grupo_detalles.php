<?php
// Si usas sesiones o configuraciones, inclúyelas aquí
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
  <link rel="stylesheet" href="css/style.css"> <!-- Ajusta la ruta a tu CSS -->
</head>
<body>

  <!-- Barra de navegación (ya incluida con nav.php) -->

  <!-- Contenedor principal de detalles -->
  <div class="details-container">
    
    <!-- Sidebar izquierdo: Nombre del grupo + participantes -->
    <div class="details-sidebar">
      <h2>Viaje Fin de grado</h2>
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

    <!-- Sección central: Gráfico circular (puedes usar un <canvas> o una imagen) -->
    <div class="chart-container">
      <!-- Aquí un placeholder con una imagen de ejemplo -->
      <!-- Reemplaza "img/piechart.png" por la ruta real de tu imagen o un <canvas> si usas librerías de gráficos -->
      <img src="img/piechart.png" alt="Gráfico circular" style="max-width: 100%; height: auto;">
    </div>

    <!-- Sidebar derecho: Botones de acción (Objetivo, Gastos) -->
    <div class="buttons-sidebar">
      <button>Objetivo</button>
      <button>Gastos</button>
    </div>

  </div>

</body>
</html>
