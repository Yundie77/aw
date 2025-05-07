<?php
require_once __DIR__ . '/includes/config.php';
use es\ucm\fdi\aw\Usuario;

header('Content-Type: text/plain');

$user = $_GET['user'] ?? '';

ini_set('display_errors', 0);
error_reporting(0);

if (Usuario::buscaUsuario($user)) {
    echo "existe";
} else {
    echo "disponible";
}
?>