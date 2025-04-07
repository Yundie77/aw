<?php
session_start(); // Inicia la sesión del usuario

// Incluye la configuración y la clase FormularioGrupoDetallesGastos
require_once 'includes/config.php';
require_once __DIR__ . '/includes/clases/FormularioGrupoDetalles.php';

// Obtiene la conexión a la base de datos y el ID del grupo
$app = \es\ucm\fdi\aw\Aplicacion::getInstance(); // Obtiene la instancia de la aplicación
$conn = $app->getConexionBd(); // Obtiene la conexión a la base de datos
$group_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Obtiene el ID del grupo desde la URL

if (!$group_id) {
    die("El grupo no está especificado."); // Termina la ejecución si no se especifica un grupo
}

// Crea una instancia de FormularioGrupoDetallesGastos
$formularioDetalles = new \es\ucm\fdi\aw\FormularioGrupoDetallesGastos($conn);

// Genera el contenido de los detalles del grupo
$contenidoPrincipal = $formularioDetalles->generarContenidoDetalles($group_id);

// Establece el título de la página utilizando el nombre del grupo
$grupo = $formularioDetalles->obtenerGrupo($group_id); // Obtiene los datos del grupo
$tituloPagina = "Grupo Detalles: " . ($grupo ? htmlspecialchars($grupo['nombre']) : 'Desconocido'); // Define el título de la página

// Incluye la plantilla para renderizar la página
require __DIR__ . '/includes/vistas/plantilla/plantilla.php';