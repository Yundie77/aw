<?php
session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/clases/Grupos.php'; // Ajusta si está en otra ruta

use es\ucm\fdi\aw\Aplicacion;


$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();

$grupoId = $_POST['grupo_id'] ?? null;
$usuarioId = $_POST['usuario_id'] ?? null;
$rol = $_POST['rol_grupo'] ?? 'miembro';

if (!$grupoId || !$usuarioId) {
    echo json_encode(['error' => 'Parámetros incompletos.']);
    exit;
}

$grupos = new Grupos($conn);

// El ID del usuario que realiza la acción (para validación de admins)
$accionPorUsuarioId = $_SESSION['user_id'] ?? null;

try {
    $resultado = $grupos->agregarMiembro((int)$grupoId, (int)$usuarioId, $rol, $accionPorUsuarioId);
    echo json_encode($resultado);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Excepción: ' . $e->getMessage()]);
}
