<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/vistas/comun/header.php';
require_once __DIR__ . '/includes/vistas/comun/nav.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - CampusCash</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <?php 
    if(isset($_SESSION['error'])) { 
        echo "<p style='color:red;'>".$_SESSION['error']."</p>"; 
        unset($_SESSION['error']);
    }
    ?>
   
    <form method="post" action="procesarLogin.php">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>
        <br>
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Ingresar</button>
    </form>
   
</body>
</html>
