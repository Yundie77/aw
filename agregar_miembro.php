<?php
session_start();

require_once __DIR__ . '/includes/config.php';

use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();

$grupoId = $_POST['grupo_id'] ?? null;
$usuarioId = $_POST['usuario_id'] ?? null;
$rol = $_POST['rol_grupo'] ?? 'miembro';

// Verificar si los parámetros están completos
if (!$grupoId || !$usuarioId) {
    echo json_encode(['error' => 'Parámetros incompletos.']);
    exit;
}

// Verificar si el usuario ya está en el grupo
$stmt = $conn->prepare("SELECT * FROM grupo_usuarios WHERE grupo_id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $grupoId, $usuarioId);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(['error' => 'El usuario ya es miembro de este grupo.']);
    exit;
}
$stmt->close();

// Verificar si el usuario es admin del grupo (si intenta agregar un admin)
if ($rol === 'admin_grupo') {
    $userId = $_SESSION['user_id']; // Usuario que realiza la acción

    // Comprobamos si el usuario actual es admin del grupo
    $stmt = $conn->prepare("SELECT * FROM grupo_usuarios WHERE grupo_id = ? AND usuario_id = ? AND rol_grupo = 'admin_grupo'");
    $stmt->bind_param("ii", $grupoId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Si el usuario no es admin, no se le permite agregar a un admin
        echo json_encode(['error' => 'No tienes permiso para agregar un admin al grupo.']);
        exit;
    }
    $stmt->close();
}

/// Insertar el nuevo miembro en el grupo
$stmt = $conn->prepare("INSERT INTO grupo_usuarios (grupo_id, usuario_id, rol_grupo) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $grupoId, $usuarioId, $rol);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Miembro agregado correctamente al grupo.']);
    exit;
} else {
    echo json_encode(['error' => 'Error al agregar el miembro.']);
    exit;
}

?>
