<?php
session_start();
require_once '../config.php';  // Archivo para conectar a la BD

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = 1; // Suponiendo que el usuario está autenticado
    $tipo = $_POST['tipo'] ?? 'Gasto'; // 'Ingreso' o 'Gasto'
    $categoria = $_POST['categoria'];
    $monto = floatval($_POST['monto']); // Monto siempre positivo
    $fecha = $_POST['fecha'];
    $comentario = $_POST['comentario'];

    $sql = "INSERT INTO gastos (usuario_id, tipo, categoria, monto, fecha, comentario)
        VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issdss", $usuario_id, $tipo, $categoria, $monto, $fecha, $comentario);

    if ($stmt->execute()) {
        echo "Gasto registrado correctamente.";
        header("Location: ../views/gastos.php");  // Redirigir de vuelta al formulario
    } else {
        echo "Error al registrar el gasto.";
    }

    $stmt->close();
    $conn->close();
}
?>
