<?php
session_start();
require_once __DIR__ . '/config.php';

// Limpieza de variables de sesión y destrucción de la sesión
unset($_SESSION["login"]);
unset($_SESSION["nombre"]);
unset($_SESSION["user_id"]);
if (isset($_SESSION["esAdmin"])) {
    unset($_SESSION["esAdmin"]);
}
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cerrar Sesión</title>
    <link rel="stylesheet" type="text/css" href="<?php echo RUTA_CSS; ?>estilo.css">
</head>
<body>
    <?php include __DIR__ . '/includes/vistas/comun/header.php'; ?>
    <?php include __DIR__ . '/includes/vistas/comun/nav.php'; ?>
    <main>
        <h2>Sesión Cerrada</h2>
        <p>Gracias por visitar nuestra web. Hasta pronto.</p>
    </main>
</body>
</html>
