<?php
require_once '../includes/config.php';

ob_start();
?>

<main>
  <h1 style="text-align: center;">Galería de Bocetos</h1>

  <p>Bienvenido a mi página de bocetos. Aquí podrás ver algunos de mis dibujos más recientes.</p>

  <div>
    <!-- Boceto 1 -->
    <div>
      <img src="<?= RUTA_IMGS ?>Inicio.jpeg" alt="Boceto 1">
      <p>Tiene la funcionalidad 1 y 2: página principal con informes del mes, gráficos de ingresos/gastos (por mes, día, semana), últimos movimientos y la funcionalidad principal de ingresar cuentas (ingreso o gasto), el día, si es recurrente, comentarios y categorías.</p>
    </div>

    <!-- Boceto 2 -->
    <div>
      <img src="<?= RUTA_IMGS ?>Grupo.jpeg" alt="Boceto 2">
      <p>Tiene la funcionalidad 3: en esta página podremos escoger a qué grupo entrar, el número de participantes por grupo y el objetivo total del grupo.</p>
    </div>

    <!-- Boceto 3 -->
    <div>
      <img src="<?= RUTA_IMGS ?>Grupo1.jpeg" alt="Boceto 3">
      <p>Se visualizan las funcionalidades 2 y 3: dentro del grupo se aprecia la lista de integrantes junto a lo que han aportado, una gráfica central que representa las aportaciones individuales respecto al objetivo y en el lateral derecho botones de "Objetivo" y "Gastos".</p>
    </div>

    <!-- Boceto 4 -->
    <div>
      <img src="<?= RUTA_IMGS ?>Gastos.jpeg" alt="Boceto 4">
      <p>Funcionalidad 3: sección de gastos que muestra una columna con los gastos del grupo y el balance de cada integrante. En el futuro se mostrará el balance total y lo que debe cada participante.</p>
    </div>

    <!-- Boceto 5 -->
    <div>
      <img src="<?= RUTA_IMGS ?>Chat.png" alt="Boceto 5">
      <p>Funcionalidad 4: diseño del chat en la página, mostrando el chat en el lateral de la página principal.</p>
    </div>

    <!-- Boceto 6 -->
    <div>
      <img src="<?= RUTA_IMGS ?>Administrador.jpeg" alt="Boceto 6">
      <p>Funcionalidad 5: panel de administrador, donde se podrá buscar usuarios por 'id' o 'nombre'. Se muestra la tabla con género, email, permisos y estado.</p>
    </div>
  </div>
</main>
<?php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Bocetos - CampusCash";
require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';
?>