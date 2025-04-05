<?php
session_start();
require_once 'includes/config.php';
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID inválido");
}

$user_id = $_SESSION['user_id'];
$sql = "DELETE FROM gastos WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $user_id);
if ($stmt->execute()) {
    header("Location: historial_gastos.php");
    exit;
} else {
    echo "Error al eliminar el registro.";
}
$stmt->close();
$conn->close();
?>
