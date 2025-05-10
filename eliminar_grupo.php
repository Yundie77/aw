<?php
session_start();

require_once 'includes/config.php';
require_once __DIR__ . '/includes/clases/Grupos.php'; // Ajusta la ruta según la ubicación real

use es\ucm\fdi\aw\Aplicacion;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grupo_id = (int) ($_POST['grupo_id'] ?? 0);

    if (empty($grupo_id)) {
        http_response_code(400);
        echo json_encode(['error' => 'El ID del grupo es obligatorio.']);
        exit;
    }

    $app = Aplicacion::getInstance();
    $conn = $app->getConexionBd();
    $grupos = new Grupos($conn);

    try {
        if ($grupos->eliminarGrupo($grupo_id)) {
            echo json_encode(['success' => 'Grupo eliminado correctamente.']);
        } else {
            echo json_encode(['error' => 'No se pudo eliminar el grupo.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.']);
    exit;
}
