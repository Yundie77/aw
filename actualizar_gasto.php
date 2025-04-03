<?php
session_start();
require_once 'includes/config.php';
use es\ucm\fdi\aw\Gastos; 

$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $usuario_id = $_SESSION['user_id'];
    $tipo = $_POST['tipo'] ?? 'Gasto';
    $monto = floatval($_POST['monto']);
    $fecha = $_POST['fecha'];
    $comentario = $_POST['comentario'] ?? '';

    $gastos = new Gastos($conn);
    if ($gastos->actualizarGasto($id, $usuario_id, $tipo, $monto, $fecha, $comentario)) {
        header("Location: historial_gastos.php");
        exit;
    } else {
        echo "Error al actualizar el registro.";
    }
}
?>
