<?php

/**
 * Parámetros de conexión a la BD
 */
define('BD_HOST', 'localhost'); 
define('BD_USER', 'awp2'); 
define('BD_NAME', 'aw');
define('BD_PASS', 'awpass');


/**
 * Parámetros de configuración utilizados para generar las URLs y las rutas a ficheros en la aplicación
 */
define('RAIZ_APP', __DIR__);
define('RUTA_APP', '/aw');
define('RUTA_IMGS', RUTA_APP . 'img/');
define('RUTA_CSS', RUTA_APP . 'css/');
define('RUTA_JS', RUTA_APP . 'js/');

/**
 * Configuración de UTF-8, localización y zona horaria
 */
ini_set('default_charset', 'UTF-8');
setLocale(LC_ALL, 'es_ES.UTF-8');
date_default_timezone_set('Europe/Madrid');

/**
 * Función para autocargar clases siguiendo PSR-4.
 */
spl_autoload_register(function ($class) {
    $prefix = 'es\\ucm\\fdi\\aw\\';

    $base_dir = __DIR__ . DIRECTORY_SEPARATOR . 'clases' . DIRECTORY_SEPARATOR;

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);

    $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});


/* Inicialización de la aplicación */

define('INSTALADA', true);
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$app->init([
    'host' => BD_HOST,
    'bd' => BD_NAME,
    'user' => BD_USER,
    'pass' => BD_PASS
]);

if (!INSTALADA) {
    die('Error 502: La aplicación no está configurada. Tienes que modificar el fichero config.php');
}

?>
