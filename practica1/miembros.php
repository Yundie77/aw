<?php
require_once '../includes/config.php';
ob_start();
?>

<main>
  <h1>Miembros del Grupo</h1>

  <ul>
    <li><a href="#miembro1">Vahram Saakian</a></li>
    <li><a href="#miembro2">Yundie Wang</a></li>
    <li><a href="#miembro3">Natalia Pego Martínez</a></li>
    <li><a href="#miembro4">Hua de Wu Lin</a></li>
    <li><a href="#miembro5">Junhua Li Li</a></li>
  </ul>

  <section id="miembro1">
    <h2>Vahram Saakian</h2>
    <img src="<?= RUTA_IMGS ?>vahram.jpg" alt="Foto de Vahram Saakian" width="150">
    <p>Email: vahramsa@ucm.es</p>
    <p>Aficiones: Amante del senderismo, la tecnología y la fotografía de paisajes.</p>
  </section>

  <section id="miembro2">
    <h2>Yundie Wang</h2>
    <img src="<?= RUTA_IMGS ?>yundie.jpg" alt="Foto de Yundie Wang" width="150">
    <p>Email: yundie01@ucm.es</p>
    <p>Aficiones: Apasionado por la lectura, el cine independiente y los viajes culturales.</p>
  </section>

  <section id="miembro3">
    <h2>Natalia Pego Martínez</h2>
    <img src="<?= RUTA_IMGS ?>natalia.jpg" alt="Foto de Natalia Pego Martínez" width="150">
    <p>Email: npego@ucm.es</p>
    <p>Aficiones: Entusiasta del ciclismo, la música clásica y los videojuegos retro.</p>
  </section>

  <section id="miembro4">
    <h2>Hua de Wu Lin</h2>
    <img src="<?= RUTA_IMGS ?>hua.jpg" alt="Foto de Hua de Wu Lin" width="150">
    <p>Email: huawu@ucm.es</p>
    <p>Aficiones: Disfruta de la cocina creativa, la pintura y el yoga.</p>
  </section>

  <section id="miembro5">
    <h2>Junhua Li Li</h2>
    <img src="<?= RUTA_IMGS ?>junhua.jpg" alt="Foto de Junhua Li Li" width="150">
    <p>Email: juli@ucm.es</p>
    <p>Aficiones: Fan del fútbol, la programación y la astronomía.</p>
  </section>
</main>

<?php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Miembros - CampusCash";
require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';
?>
