<?php
require_once __DIR__ . '/includes/config.php';
use es\ucm\fdi\aw\Usuario;

header('Content-Type: application/json');
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

$id = intval($_POST['id']);
$user = Usuario::buscaPorId($conn, $id);
$nuevoEstado = ($user->estado === 'activo') ? 'inactivo' : 'activo';

$ok = Usuario::actualizaEstado($conn, $id, $nuevoEstado);
echo json_encode(['success' => $ok]);
