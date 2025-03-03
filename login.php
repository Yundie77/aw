<?php
session_start();
require 'db.php';
require '../includes/header.php';
require '../includes/nav.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - CampusCash</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="login.php">
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
