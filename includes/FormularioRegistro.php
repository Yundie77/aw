<?php

require_once __DIR__ . '/Formulario.php';
require_once __DIR__ . '/clases/Usuario.php';

class FormularioRegistro extends Formulario {
    public function __construct() {
        parent::__construct('formRegistro', ['urlRedireccion' => 'index.php']);
    }

    protected function generaCamposFormulario(&$datos) {
        // Funcion proporcioanada por chatGPT: explicada en gastos.php
        ob_start();
        ?>
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($datos['nombre'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($datos['email'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmar Contraseña</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <?php
        return ob_get_clean();
    }

    protected function procesaFormulario(&$datos) {
        global $conn;
        
        $nombre = trim($datos['nombre']);
        $email = trim($datos['email']);
        $password = $datos['password'];
        $confirmPassword = $datos['confirm_password'];

        if ($password !== $confirmPassword) {
            echo "<p class='error-message'>Las contraseñas no coinciden.</p>";
        } else {
            $usuarioExistente = Usuario::buscaUsuario($conn, $email);
            if ($usuarioExistente) {
                echo "<p class='error-message'>El correo ya está registrado.</p>";
            } else {
                if (Usuario::creaUsuario($conn, $nombre, $email, $password)) {
                    echo "<p class='success-message'>Registro exitoso. Ahora puedes iniciar sesión.</p>";
                } else {
                    echo "<p class='error-message'>Error al registrar el usuario.</p>";
                }
            }
        }
    }
}
?>
