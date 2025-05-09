<?php
require_once __DIR__ . '/includes/config.php';

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Mensajes;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['exito' => false, 'error' => 'Usuario no autenticado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['exito' => false, 'error' => 'Método no permitido.']);
    exit;
}

$id_grupo = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;
$contenido = isset($_POST['contenido']) ? trim($_POST['contenido']) : '';
$id_usuario = $_SESSION['user_id'];

if ($id_grupo <= 0) {
    echo json_encode(['exito' => false, 'error' => 'ID de grupo no válido.']);
    exit;
}

if (empty($contenido)) {
    echo json_encode(['exito' => false, 'error' => 'El mensaje no puede estar vacío.']);
    exit;
}

$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();

$stmt_verif = $conn->prepare("SELECT COUNT(*) FROM grupo_usuarios WHERE grupo_id = ? AND usuario_id = ?");
if (!$stmt_verif) {
    error_log("Error al preparar verificación de membresía para enviar: " . $conn->error);
    echo json_encode(['exito' => false, 'error' => 'Error interno del servidor.']);
    exit;
}
$stmt_verif->bind_param("ii", $id_grupo, $id_usuario);
$stmt_verif->execute();
$stmt_verif->bind_result($conteo);
$stmt_verif->fetch();
$stmt_verif->close();

if ($conteo == 0 && (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin')) {
    echo json_encode(['exito' => false, 'error' => 'No puedes enviar mensajes a este grupo.']);
    exit;
}

$manejadorMensajes = new Mensajes($conn);

if ($manejadorMensajes->insertarMensaje($id_usuario, $id_grupo, $contenido)) {
    echo json_encode(['exito' => true, 'mensaje' => 'Mensaje enviado.']);
} else {
    echo json_encode(['exito' => false, 'error' => 'Error al enviar el mensaje.']);
}
?>