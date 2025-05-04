<?php
require_once 'includes/config.php';

ob_start();
?>
<main class="mantenimiento-main">
  <div class="mantenimiento-container">
    <h1>🚧Estamos en mantenimiento🚧</h1>
    <p>
        Estamos realizando mejoras para brindarte una mejor experiencia.<br>
        Por favor vuelve más tarde. ¡Gracias por tu paciencia!
    </p>
  </div>
</main>

<?php

$contenidoPrincipal = ob_get_clean();
$tituloPagina = "CampusCash - Mantenimiento";

require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';
?>
