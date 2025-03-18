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
<div class="login-container">
        <h2>Iniciar Sesión</h2>

        <?php
        if(isset($_SESSION['error'])) {
            echo "<p class='error-message'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>

        <form action="procesarLogin.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Ingrese su nombre">
            </div>

            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" required placeholder="Ingrese su usuario">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="Ingrese su contraseña">
            </div>

            <button type="submit" class="btn">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
