<?php
session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/includes/config.php';

use es\ucm\fdi\aw\Aplicacion;

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Solo los administradores pueden agregar miembros.");
}

$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();

$grupoId = $_POST['grupo_id'] ?? null;
$usuarioId = $_POST['usuario_id'] ?? null;
$rol = $_POST['rol_grupo'] ?? 'miembro';

if (!$grupoId || !$usuarioId) {
    echo json_encode(['error' => 'Parámetros incompletos.']);
    exit;
}

// Verificar si el grupo existe
$stmt = $conn->prepare("SELECT id FROM grupos WHERE id = ?");
$stmt->bind_param("i", $grupoId);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    echo json_encode(['error' => 'Grupo no encontrado.']);
    exit;
}
$stmt->close();

// Verificar si el usuario existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    echo json_encode(['error' => 'Usuario no encontrado.']);
    exit;
}
$stmt->close();

// Verificar si ya está en el grupo
$stmt = $conn->prepare("SELECT * FROM grupo_usuarios WHERE grupo_id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $grupoId, $usuarioId);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(['error' => 'El usuario ya es miembro del grupo.']);
    exit;
}
$stmt->close();

// Insertar en grupo_usuarios
$stmt = $conn->prepare("INSERT INTO grupo_usuarios (grupo_id, usuario_id, rol_grupo) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $grupoId, $usuarioId, $rol);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Miembro agregado correctamente al grupo.']);
} else {
    echo json_encode(['error' => 'Error al agregar el miembro.']);
}
$stmt->close();
?>
