<?php
require_once __DIR__ . '/includes/config.php';
use es\ucm\fdi\aw\Usuario;

ini_set('display_errors', 0);
error_reporting(0);

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (Usuario::buscaCorreo($email)) {
        echo "existe";
    } else {
        echo "disponible";
    }
}
?>