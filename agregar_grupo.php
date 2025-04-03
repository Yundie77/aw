<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nombre = trim($_POST['nombre']);
    $objetivo = trim($_POST['objetivo']);
    $descripcion = trim($_POST['descripcion']);
    
    if (empty($nombre) || !is_numeric($objetivo) || empty($descripcion)) {
         echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
         exit;
    }
    // Preparar la inserción con el nuevo campo "descripcion"
    $stmt = $conn->prepare("INSERT INTO grupos (nombre, objetivo, descripcion) VALUES (?, ?, ?)");
    // Suponiendo que "objetivo" es numérico (double) y "nombre" y "descripcion" son cadenas
    $stmt->bind_param("sds", $nombre, $objetivo, $descripcion);
    if ($stmt->execute()) {
         echo json_encode(['success' => true]);
    } else {
         echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    exit;
}