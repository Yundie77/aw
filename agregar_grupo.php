<?php
session_start();

require_once 'includes/config.php';
require_once __DIR__ . '/includes/clases/Grupos.php'; // ajusta si es necesario

use es\ucm\fdi\aw\Aplicacion;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $objetivo = trim($_POST['objetivo'] ?? '');
    $usuario_id = $_SESSION['user_id'] ?? null;

    if (!$usuario_id) {
        echo json_encode(['error' => 'Usuario no autenticado.']);
        exit;
    }

    $app = Aplicacion::getInstance();
    $conn = $app->getConexionBd();
    $grupos = new Grupos($conn);

    try {
        $resultado = $grupos->crearGrupo($nombre, $objetivo, $usuario_id);
        echo json_encode($resultado);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error interno: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.']);
}
