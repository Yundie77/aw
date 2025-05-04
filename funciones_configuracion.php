<?php
function cambiarModoMantenimiento($estado) {
    $configPath = __DIR__ . '/includes/config_app.php';
    $config = include($configPath);
    $config['maintenance_mode'] = $estado;

    $contenido = "<?php\nreturn " . var_export($config, true) . ";\n";
    file_put_contents($configPath, $contenido);
}


