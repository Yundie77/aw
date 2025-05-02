<?php
require_once __DIR__ . '/includes/config.php';

use es\ucm\fdi\aw\Aplicacion;

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
$sql = "
    SELECT DATE_FORMAT(fecha_creacion, '%Y-%m') AS mes, COUNT(*) AS total
    FROM usuarios
    GROUP BY mes
    ORDER BY mes ASC
";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        'mes' => $row['mes'],
        'total' => (int)$row['total']
    ];
}

$conn->close();

// Devolver como JSON
header('Content-Type: application/json');
echo json_encode($data);
