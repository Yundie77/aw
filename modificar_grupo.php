<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/includes/config.php';

    $grupo_id = (int) ($_POST['grupo_id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $objetivo = trim($_POST['objetivo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if (empty($grupo_id) || empty($nombre) || empty($objetivo) || empty($descripcion)) {
        die("Error: Todos los campos son obligatorios.");
    }

    $app = \es\ucm\fdi\aw\Aplicacion::getInstance();
    $conn = $app->getConexionBd();

    $stmt = $conn->prepare("UPDATE grupos SET nombre = ?, objetivo = ?, descripcion = ? WHERE id = ?");
    $stmt->bind_param("sisi", $nombre, $objetivo, $descripcion, $grupo_id);

    if ($stmt->execute()) {
        $_SESSION['mensaje_exito'] = "Operación exitosa";
        header("Location: grupos.php?mensaje=grupo_modificado");
        exit;
    } else {
        die("Error al modificar el grupo: " . $conn->error);
    }
} else {
    die("Método no permitido.");
}