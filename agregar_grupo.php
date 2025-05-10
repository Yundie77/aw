<?php
session_start();

require_once 'includes/config.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre'] ?? '');
		$objetivo = trim($_POST['objetivo'] ?? '');

        if (empty($nombre) || empty($objetivo)) {
            echo json_encode(['error' => 'Todos los campos son obligatorios.']);
            exit;
        }

		$usuario_id = $_SESSION['user_id'];
        $app = \es\ucm\fdi\aw\Aplicacion::getInstance();
        $conn = $app->getConexionBd();

		$nombre = $conn->real_escape_string($nombre);
		$objetivo = $conn->real_escape_string($objetivo);

        // Check if a group with the same name already exists
        $stmt_check = $conn->prepare("SELECT id FROM grupos WHERE nombre = ?");
        $stmt_check->bind_param("s", $nombre);
        $stmt_check->execute();
        $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo json_encode(['error' => 'Ya existe un grupo con este nombre.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO grupos (nombre, objetivo) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombre, $objetivo);

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
