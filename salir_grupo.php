<?php
require_once 'includes/config.php';
require_once 'includes/clases/Grupos.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Debes iniciar sesión para realizar esta acción.']);
    exit;
}

$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $grupo_id = filter_input(INPUT_POST, 'grupo_id', FILTER_SANITIZE_NUMBER_INT);
    $usuario_id = $_SESSION['user_id'];

    if ($grupo_id === false || $usuario_id === false) {
        echo json_encode(['error' => 'ID de grupo o usuario inválido.']);
        exit;
    }

    $grupos = new Grupos($conn);
    $resultado = $grupos->salirGrupo($grupo_id, $usuario_id);

    echo json_encode($resultado);
    exit;
} else {
    echo json_encode(['error' => 'Método no permitido.']);
    exit;
}
