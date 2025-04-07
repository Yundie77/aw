<?php
session_start(); // Inicia la sesión del usuario

// Incluimos la configuración y el formulario de grupos
require_once 'includes/config.php'; 
require_once 'includes/clases/FormularioGrupoDetallesGastos.php'; 

// Obtiene la instancia de la aplicación
$app = \es\ucm\fdi\aw\Aplicacion::getInstance(); 

// Obtiene la conexión a la base de datos
$conn = $app->getConexionBd(); 

// Obtiene el ID del grupo desde la URL
$group_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; 
if (!$group_id) {
  die("El grupo no está especificado."); // Termina la ejecución si no se especifica un grupo
}

// Crea una instancia del formulario de detalles de gastos
$formularioGastos = new \es\ucm\fdi\aw\FormularioGrupoDetallesGastos($conn); 

// Inicia el almacenamiento en búfer de salida
ob_start(); 

// Genera el contenido de los gastos del grupo
echo $formularioGastos->generarContenidoGastos($group_id);

// Captura el contenido generado y lo almacena en una variable
$contenidoPrincipal = ob_get_clean(); 

// Define el título de la página
$tituloPagina = "Grupo Detalles Gastos: " . htmlspecialchars($formularioGastos->obtenerNombreGrupo($group_id)); 

// Incluye la plantilla para renderizar la página
require __DIR__ . '/includes/vistas/plantilla/plantilla.php'; 