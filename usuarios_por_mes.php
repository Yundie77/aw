<?php
require_once __DIR__ . '/includes/config.php';

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Usuario;

// Solo permitir acceso si es admin
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();

// Consulta para contar usuarios nuevos por mes
$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();

// Llama al nuevo método estático de Usuario
$data = Usuario::getUsuariosNuevosPorMes($conn);
$conn->close();

// Devolver como JSON
header('Content-Type: application/json');
echo json_encode($data);
