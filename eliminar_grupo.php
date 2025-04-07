<?php
session_start();

require_once 'includes/config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/includes/config.php';

    $grupo_id = (int) ($_POST['grupo_id'] ?? 0);

    if (empty($grupo_id)) {
        die("Error: El ID del grupo es obligatorio.");
    }

    $app = \es\ucm\fdi\aw\Aplicacion::getInstance();
    $conn = $app->getConexionBd();

    $stmt = $conn->prepare("DELETE FROM grupos WHERE id = ?");
    $stmt->bind_param("i", $grupo_id);

    if ($stmt->execute()) {
        $_SESSION['mensaje_exito'] = "Operación exitosa";
        header("Location: grupos.php");
        exit;
    } else {
        die("Error al eliminar el grupo: " . $conn->error);
    }
} else {
    die("Método no permitido.");
}
?>