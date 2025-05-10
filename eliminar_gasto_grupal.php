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
$gastos = new \es\ucm\fdi\aw\GastosGrupales($conn);

$grupo_id = isset($_GET['grupo_id']) ? intval($_GET['grupo_id']) : 0;


if ($gastos->eliminarGasto($id, $user_id)) {
    header("Location: historial_gasto_grupal.php?id=" . $grupo_id);

    exit;
}
 else {
    echo "Error al eliminar el registro.";
}

$conn->close();
?>
