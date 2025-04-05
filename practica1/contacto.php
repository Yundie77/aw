<?php
require_once '../includes/config.php';

ob_start();
?>

<main>
  <header>
    <h1>Contacto</h1>
  </header>

  <section>
    <h2>Formulario de Contacto</h2>
    <form action="mailto:contacto@campuscash.com" method="post" enctype="text/plain">
      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" required><br><br>

      <label for="email">Correo Electrónico:</label>
      <input type="email" id="email" name="email" required><br><br>

      <p>Motivo de la consulta:</p>
      <input type="radio" id="evaluacion" name="motivo" value="Evaluación" required>
      <label for="evaluacion">Evaluación</label><br>

      <input type="radio" id="sugerencias" name="motivo" value="Sugerencias">
      <label for="sugerencias">Sugerencias</label><br>

      <input type="radio" id="criticas" name="motivo" value="Críticas">
      <label for="criticas">Críticas</label><br><br>

      <input type="checkbox" id="terminos" name="terminos" required>
      <label for="terminos">Marque esta casilla para verificar que ha leído nuestros términos y condiciones del servicio</label><br><br>

      <label for="mensaje">Consulta:</label><br>
      <textarea id="mensaje" name="mensaje" rows="5" cols="40" required></textarea><br><br>

      <button type="submit">Enviar</button>
    </form>
  </section>
</main>

<?php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Contacto - CampusCash";
require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';
?>