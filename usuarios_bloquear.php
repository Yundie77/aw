<?php
require_once __DIR__ . '/includes/config.php';
use es\ucm\fdi\aw\Usuario;

header('Content-Type: application/json');
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

$id = intval($_POST['id']);
$bloqueadoHasta = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');

$ok = Usuario::bloquearHasta($conn, $id, $bloqueadoHasta);
echo json_encode(['success' => $ok]);
