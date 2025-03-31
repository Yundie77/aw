<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $usuario_id = $_SESSION['user_id'];
    $tipo = $_POST['tipo'] ?? 'Gasto';
    $monto = floatval($_POST['monto']);
    $fecha = $_POST['fecha'];
    $comentario = $_POST['comentario'] ?? '';
    // Si usas categoría dinámica, también procesa categoría, etc.

    $sql = "UPDATE gastos 
            SET tipo = ?, monto = ?, fecha = ?, comentario = ? 
            WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissii", $tipo, $monto, $fecha, $comentario, $id, $usuario_id);
    if ($stmt->execute()) {
        header("Location: historial_gastos.php");
        exit;
    } else {
        echo "Error al actualizar el registro.";
    }
    $stmt->close();
    $conn->close();
}
?>
