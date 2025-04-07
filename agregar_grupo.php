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
        header("Location: grupos.php?mensaje=grupo_agregado");
        exit;
    } else {
        die("Error al agregar el grupo: " . $stmt->error);
    }
} else {
    die("Método no permitido.");
}
?>
