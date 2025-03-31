<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/clases/Usuario.php';
require_once __DIR__ . '/includes/FormularioRegistro.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Las contraseñas no coinciden.";
    } else {
        $usuarioExistente = Usuario::buscaUsuario($conn, $email);
        if ($usuarioExistente) {
            $_SESSION['error'] = "El correo ya está registrado.";
        } else {
            if (Usuario::creaUsuario($conn, $nombre, $email, $password)) {
                $_SESSION['success'] = "Registro exitoso. Ahora puedes iniciar sesión.";
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['error'] = "Error al registrar el usuario.";
            }
        }
    }
}

$tituloPagina = "Registro - CampusCash";
// Funcion proporcioanada por chatGPT: explicada en gastos.php
ob_start();
?>


<div class="login-container">
    <h2>Registro</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo "<p class='error-message'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>
    <form action="registro.php" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmar Contraseña</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-green">Registrarse</button>
    </form>
</div>


<?php
// Funcion proporcioanada por chatGPT: explicada en gastos.php
$contenidoPrincipal = ob_get_clean();
require __DIR__ . '/includes/vistas/plantilla/plantilla.php';

$formulario = new FormularioRegistro();
$formulario->gestiona();
