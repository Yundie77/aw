<?php
session_start();
require_once 'includes/config.php';
require_once __DIR__ . '/includes/clases/Grupos.php'; // Asegúrate de que esta ruta sea correcta

use es\ucm\fdi\aw\Aplicacion;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grupo_id = (int) ($_POST['grupo_id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $objetivo = trim($_POST['objetivo'] ?? '');

    if (empty($grupo_id) || empty($nombre) || empty($objetivo) ) {
        http_response_code(400);
        echo json_encode(['error' => 'Todos los campos son obligatorios.']);
        exit();
    }

    $app = Aplicacion::getInstance();
    $conn = $app->getConexionBd();
    $grupos = new Grupos($conn);

    try {
        if ($grupos->actualizarGrupo($grupo_id, $nombre, $objetivo)) {
            echo json_encode(['success' => 'Grupo modificado correctamente.']);
        } else {
            echo json_encode(['error' => 'No se pudo modificar el grupo.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
    exit();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.']);
    exit();
}
