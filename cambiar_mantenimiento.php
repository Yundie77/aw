<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/funciones_configuracion.php';



header('Content-Type: application/json');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$config = include(__DIR__ . '/includes/config_app.php');
if (!is_array($config) || !isset($config['maintenance_mode'])) {
    echo json_encode(['error' => 'Configuración inválida']);
    exit();
}

$nuevoEstado = !$config['maintenance_mode'];
cambiarModoMantenimiento($nuevoEstado);

echo json_encode(['estado' => $nuevoEstado ? 'Activado' : 'Desactivado']);
