<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Include the application configuration and class
require_once 'includes/config.php';

// Get the database connection from the Aplicacion singleton
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grupo_id = (int) $_POST['grupo_id'];
    $usuario_id = (int) $_POST['usuario_id'];
    $monto = trim($_POST['monto']); // Variable name kept as $monto for consistency

    if ($grupo_id <= 0 || $usuario_id <= 0 || !is_numeric($monto) || $monto < 0) {
        echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
        exit;
    }

    if (!$conn) {
        echo json_encode(['success' => false, 'error' => 'No se pudo conectar a la base de datos.']);
        exit;
    }

    $stmtCheck = $conn->prepare("SELECT * FROM grupo_usuarios WHERE grupo_id = ? AND usuario_id = ?");
    if (!$stmtCheck) {
        echo json_encode(['success' => false, 'error' => 'Error preparando la consulta: ' . $conn->error]);
        exit;
    }
    $stmtCheck->bind_param("ii", $grupo_id, $usuario_id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'El miembro ya está agregado al grupo.']);
        exit;
    }

    // Updated query to use 'amount' instead of 'monto'
    $stmt = $conn->prepare("INSERT INTO grupo_usuarios (grupo_id, usuario_id, amount) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Error preparando la inserción: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("iid", $grupo_id, $usuario_id, $monto);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    exit;
}
echo json_encode(['success' => false, 'error' => 'Método no permitido']);
exit;