<?php
session_start();
require_once __DIR__ . '/includes/config.php';

// Limpieza de variables de sesión y destrucción de la sesión
unset($_SESSION["login"]);
unset($_SESSION["nombre"]);
unset($_SESSION["user_id"]);
if (isset($_SESSION["esAdmin"])) {
    unset($_SESSION["esAdmin"]);
}
session_destroy();

header("Location: login.php");
exit();
?>
