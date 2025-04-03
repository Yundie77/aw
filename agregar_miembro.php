<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json'); // ChatGPT

// Asegúrate de que no se envíe ninguna salida antes de este punto
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $grupo_id = (int) $_POST['grupo_id'];
    $usuario_id = (int) $_POST['usuario_id'];
    $monto = trim($_POST['monto']);
    
    if ($grupo_id <= 0 || $usuario_id <= 0 || !is_numeric($monto)) {
         echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
         exit;
    }
    // Verificar si el miembro ya está en el grupo
    $stmtCheck = $conn->prepare("SELECT * FROM grupo_usuarios WHERE grupo_id = ? AND usuario_id = ?");
    $stmtCheck->bind_param("ii", $grupo_id, $usuario_id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    if ($result->num_rows > 0) {
         echo json_encode(['success' => false, 'error' => 'El miembro ya está agregado al grupo.']);
         exit;
    }
    // Insertar el nuevo registro, incluyendo el monto invertido
    $stmt = $conn->prepare("INSERT INTO grupo_usuarios (grupo_id, usuario_id, monto) VALUES (?, ?, ?)");
    $stmt->bind_param("iid", $grupo_id, $usuario_id, $monto);
    if ($stmt->execute()) {
         echo json_encode(['success' => true]);
    } else {
         echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    exit;
}