<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $grupo_id = (int) $_POST['grupo_id'];
    if($grupo_id <= 0){
         echo json_encode(['success' => false, 'error' => 'ID inválido.']);
         exit;
    }
    $stmt = $conn->prepare("DELETE FROM grupos WHERE id = ?");
    $stmt->bind_param("i", $grupo_id);
    if($stmt->execute()){
         echo json_encode(['success' => true]);
    } else {
         echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    exit;
}