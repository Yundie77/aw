<?php
session_start();

require_once 'includes/config.php';
require_once 'includes/clases/FormularioGrupoDetallesGastos.php';

$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();
$group_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$group_id) {
  die("El grupo no está especificado.");
}

$formularioGastos = new \es\ucm\fdi\aw\FormularioGrupoDetallesGastos($conn);

ob_start();
echo $formularioGastos->generarContenidoGastos($group_id);
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Grupo Detalles Gastos: " . htmlspecialchars($formularioGastos->obtenerNombreGrupo($group_id));
require __DIR__ . '/includes/vistas/plantilla/plantilla.php';