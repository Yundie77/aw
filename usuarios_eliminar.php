<?php
require_once __DIR__ . '/includes/config.php';
use es\ucm\fdi\aw\Usuario;

header('Content-Type: application/json');

$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $ok = Usuario::eliminarPorId($conn, $id);
    echo json_encode(['success' => $ok, 'mensaje' => $ok ? 'Usuario eliminado' : 'Error al eliminar']);
} else {
    echo json_encode(['success' => false, 'mensaje' => 'ID no válido']);
}

