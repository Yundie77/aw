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

$id_grupo = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;

if ($id_grupo <= 0) {
    echo json_encode(['exito' => false, 'error' => 'ID de grupo no válido.']);
    exit;
}

$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();

$usuario_id_actual = $_SESSION['user_id'];
$stmt_verif = $conn->prepare("SELECT COUNT(*) FROM grupo_usuarios WHERE grupo_id = ? AND usuario_id = ?");
if (!$stmt_verif) {
    error_log("Error al preparar verificación de membresía: " . $conn->error);
    echo json_encode(['exito' => false, 'error' => 'Error interno del servidor.']);
    exit;
}
$stmt_verif->bind_param("ii", $id_grupo, $usuario_id_actual);
$stmt_verif->execute();
$stmt_verif->bind_result($conteo);
$stmt_verif->fetch();
$stmt_verif->close();

if ($conteo == 0 && (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin')) {
    echo json_encode(['exito' => false, 'error' => 'Acceso denegado al chat del grupo.']);
    exit;
}

$manejadorMensajes = new Mensajes($conn);
$mensajes = $manejadorMensajes->obtenerMensajesPorGrupo($id_grupo);

echo json_encode(['exito' => true, 'mensajes' => $mensajes, 'id_usuario_actual' => $usuario_id_actual]);
?>