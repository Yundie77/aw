<?php
session_start();

// Include configuration and the FormularioGrupoDetallesGastos class
require_once 'includes/config.php';
require_once __DIR__ . '/includes/clases/FormularioGrupoDetalles.php';

// Get the database connection and group ID
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();
$group_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$group_id) {
    die("El grupo no está especificado.");
}

// Create an instance of FormularioGrupoDetallesGastos
$formularioDetalles = new \es\ucm\fdi\aw\FormularioGrupoDetallesGastos($conn);

// Generate the content
$contenidoPrincipal = $formularioDetalles->generarContenidoDetalles($group_id);

// Set the page title using the group name
$grupo = $formularioDetalles->obtenerGrupo($group_id);
$tituloPagina = "Grupo Detalles: " . ($grupo ? htmlspecialchars($grupo['nombre']) : 'Desconocido');

// Include the template
require __DIR__ . '/includes/vistas/plantilla/plantilla.php';