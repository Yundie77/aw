<?php
// Iniciamos la sesión
session_start();

// Incluimos la configuración y el formulario de grupos
require_once 'includes/config.php';
require_once 'includes/clases/FormularioGrupos.php';

// Obtenemos la instancia de la aplicación y la conexión a la base de datos
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

// Creamos una instancia del formulario de grupos
$formularioGrupos = new \es\ucm\fdi\aw\FormularioGrupos($conn);

// Iniciamos el buffer de salida
ob_start();

// Generamos el contenido principal utilizando los métodos del formulario
echo $formularioGrupos->generarListaGrupos(); // Genera la lista de grupos
echo $formularioGrupos->generarBotones();    // Genera los botones para las ventanas modales
echo $formularioGrupos->generarModales();    // Genera las ventanas modales


// Capturamos el contenido generado en el buffer
$contenidoPrincipal = ob_get_clean();

// Título de la página
$tituloPagina = "Grupos";

// Incluimos la plantilla principal
require __DIR__ . '/includes/vistas/plantilla/plantilla.php';