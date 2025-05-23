<?php
require_once 'includes/config.php';

$config_app = include 'includes/config_app.php';
$maintenance_mode = $config_app['maintenance_mode'] ?? false;

// Funcion proporcioanada por chatGPT: explicada en gastos.php
ob_start();
?>

  <h1 class="welcome-title">Bienvenido a CampusCash</h1>

  <div class="logo-container">
   <img src="<?= RUTA_APP ?>img/logo.png" alt="Logotipo de CampusCash" class="logo-image">
  </div>

  <section class="project-description">
    <h2>Gestiona tus finanzas de forma sencilla y eficiente</h2>
    <p>
      <strong>CampusCash</strong> es una innovadora plataforma diseñada para facilitar la gestión financiera personal y grupal,
      especialmente pensada para estudiantes y usuarios que desean organizar sus gastos de manera eficiente.
      Permite registrar gastos, analizar patrones de consumo mediante gráficos detallados y colaborar en grupos para dividir gastos comunes.
      Además, incluye funciones de interacción social para discutir consejos financieros y compartir experiencias.
    </p>
  </section>

  <div class="explore-button-container">
    <a href="gastos.php" class="explore-button">Explorar CampusCash</a>
  </div>

<?php

// Funcion proporcioanada por chatGPT: explicada en gastos.php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Inicio - CampusCash";

require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';

?>
