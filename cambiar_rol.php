<?php
session_start();
require_once 'includes/config.php';
use es\ucm\fdi\aw\Admin;

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();
$admin = new Admin($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nuevoRol = $_POST['rol'] ?? 'usuario';

    if ($admin->cambiarRolUsuario($id, $nuevoRol)) {
        $_SESSION['mensaje_exito'] = "Rol actualizado correctamente.";
    } else {
        $_SESSION['mensaje_error'] = "Error al actualizar el rol.";
    }
}

header("Location: formularioadmin.php");
exit();
?>
