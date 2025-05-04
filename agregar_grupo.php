<?php
session_start();

require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $objetivo = trim($_POST['objetivo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if (empty($nombre) || empty($objetivo) || empty($descripcion)) {
        echo json_encode(['error' => 'Todos los campos son obligatorios.']);
        exit;
    }

    $usuario_id = $_SESSION['user_id'];
    $app = \es\ucm\fdi\aw\Aplicacion::getInstance();
    $conn = $app->getConexionBd();

    $stmt = $conn->prepare("INSERT INTO grupos (nombre, objetivo, descripcion) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $objetivo, $descripcion);

    if ($stmt->execute()) {
        $grupo_id = $stmt->insert_id;
        $stmt2 = $conn->prepare("INSERT INTO grupo_usuarios (grupo_id, usuario_id, rol_grupo) VALUES (?, ?, 'admin_grupo')");
        $stmt2->bind_param("ii", $grupo_id, $usuario_id);
        $stmt2->execute();

        echo json_encode(['success' => 'Grupo agregado correctamente.']);
        exit;
    } else {
        echo json_encode(['error' => 'Error al agregar grupo: ' . $stmt->error]);
        exit;
    }
} else {
    echo json_encode(['error' => 'Método no permitido.']);
    exit;
}
?>
