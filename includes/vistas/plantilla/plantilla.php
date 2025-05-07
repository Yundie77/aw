<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $tituloPagina ?></title>
    <link rel="stylesheet" type="text/css" href="<?= RUTA_CSS ?>/style.css" />
</head>
<body>
<div id="contenedor">
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$configPath = __DIR__ . '/../../config_app.php';
$excepciones = ['login.php', 'procesarLogin.php', 'logout.php', 'mantenimiento.php','index.php'];

if (file_exists($configPath)) {
    $rutaActual = basename($_SERVER['SCRIPT_NAME']);
    $config = include $configPath;

    if (
        isset($config['maintenance_mode']) &&
        $config['maintenance_mode'] &&
        !in_array($rutaActual, $excepciones) &&
        (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin')
    ) {
        header('Location: /aw/mantenimiento.php');
        exit();
    }
}

require(RAIZ_APP . '/vistas/comun/header.php');
require(RAIZ_APP . '/vistas/comun/nav.php');
?>
    <main>
        <article>
            <?= $contenidoPrincipal ?>
        </article>
    </main>
<?php
require(RAIZ_APP . '/vistas/comun/footer.php');
?>
</div>
<script src="<?= RUTA_JS ?>modal.js"></script>
<script src="<?= RUTA_JS ?>srciptMensaje.js?v=<?= time(); ?>"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="<?= RUTA_JS ?>validacionRegistro.js"></script>

</body>
</html>
