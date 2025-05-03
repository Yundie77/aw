<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    die("Debes iniciar sesión para agregar un grupo.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/includes/config.php';

    $nombre = trim($_POST['nombre'] ?? '');
    $objetivo = trim($_POST['objetivo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $usuario_id = $_SESSION['user_id'];

    if (empty($nombre) || empty($objetivo) || empty($descripcion)) {
        die("Error: Todos los campos son obligatorios.");
    }

    $app = \es\ucm\fdi\aw\Aplicacion::getInstance();
    $conn = $app->getConexionBd();

    $stmt = $conn->prepare("INSERT INTO grupos (nombre, objetivo, descripcion) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("sss", $nombre, $objetivo, $descripcion);

    if ($stmt->execute()) {
        $grupo_id = $stmt->insert_id; // ID del grupo creado
        $rol = 'admin_grupo'; 

        // Añadir al creador como miembro del grupo
        $stmt2 = $conn->prepare("INSERT INTO grupo_usuarios (grupo_id, usuario_id, rol_grupo) VALUES (?, ?, ?)");
        if (!$stmt2) {
            die("Error al preparar inserción de miembro: " . $conn->error);
        }

        $stmt2->bind_param("iis", $grupo_id, $usuario_id, $rol);

        if ($stmt2->execute()) {
            echo json_encode(['success' => 'Grupo agregado correctamente.']);
            exit;
        } else {
            echo json_encode(['error' => 'Error al agregar el grupo.']);
            exit;
        }

        $stmt2->close();
        $stmt->close();
    } else {
        die("Error al agregar el grupo: " . $stmt->error);
    }
} else {
    die("Método no permitido.");
}
?>
