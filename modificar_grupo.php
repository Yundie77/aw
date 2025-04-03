<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $grupo_id = (int) $_POST['grupo_id'];
    $nombre = trim($_POST['nombre']);
    $objetivo = trim($_POST['objetivo']);
    $descripcion = trim($_POST['descripcion']);

    if($grupo_id <= 0 || empty($nombre) || !is_numeric($objetivo) || empty($descripcion)){
         echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
         exit;
    }
    $stmt = $conn->prepare("UPDATE grupos SET nombre = ?, objetivo = ?, descripcion = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $nombre, $objetivo, $descripcion, $grupo_id);
    if($stmt->execute()){
         echo json_encode(['success' => true]);
    } else {
         echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    exit;
}