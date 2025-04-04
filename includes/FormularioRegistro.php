<?php

require_once __DIR__ . '/Formulario.php';
require_once __DIR__ . '/clases/Usuario.php';

class FormularioRegistro extends Formulario {
    public function __construct() {
        parent::__construct('formRegistro', ['urlRedireccion' => 'index.php']);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $email = $datos['email'] ?? '';
        $password = $datos['password'] ?? '';
        $confirmPassword = $datos['confirm_password'] ?? '';

        ob_start();
        // Se muestran errores globales
        echo self::generaListaErroresGlobales($this->errores, 'errores');
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <label for="nombreUsuario">Nombre de usuario</label>
                <input type="text" id="nombreUsuario" name="nombreUsuario" required placeholder="Introduce tu nombre de usuario" 
                value="<?php echo htmlspecialchars($nombreUsuario); ?>">
                <?php echo $this->errores['nombreUsuario'] ?? ''; ?>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required placeholder="Introduce tu correo electrónico" 
                value="<?php echo htmlspecialchars($email); ?>">
                <?php echo $this->errores['email'] ?? ''; ?>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="Introduce tu contraseña">
                <?php echo $this->errores['password'] ?? ''; ?>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmar Contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Repite tu contraseña">
                <?php echo $this->errores['confirm_password'] ?? ''; ?>
            </div>
            <button type="submit" class="btn btn-green">Registrar</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        <?php
        return ob_get_clean();
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $email = trim($datos['email'] ?? '');
        $password = $datos['password'] ?? '';
        $confirmPassword = $datos['confirm_password'] ?? '';

        // Validaciones
        if (empty($nombreUsuario)) {
            $this->errores['nombreUsuario'] = "El nombre de usuario no puede estar vacío.";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errores['email'] = "El correo electrónico no es válido.";
        }

        if (empty($password)) {
            $this->errores['password'] = "La contraseña no puede estar vacía.";
        }

        if ($password !== $confirmPassword) {
            $this->errores['confirm_password'] = "Las contraseñas no coinciden.";
        }

        if (count($this->errores) === 0) {
            // Verifica si el usuario ya existe
            if (Usuario::buscaUsuario($nombreUsuario)) {
                $this->errores['nombreUsuario'] = "El nombre de usuario ya está registrado.";
            } else {
                // Crea el usuario
                $usuario = Usuario::crea($nombreUsuario, $email, $password, 'usuario');
                if (!$usuario) {
                    $this->errores['global'] = "Error al registrar el usuario.";
                } else {
                    // Inicia sesión automáticamente utilizando Usuario::login
                    $usuarioLogueado = Usuario::login($nombreUsuario, $password);
                    if ($usuarioLogueado) {
                        if (session_status() !== PHP_SESSION_ACTIVE) {
                            session_start();
                        }
                        $_SESSION['user_id'] = $usuarioLogueado->id;
                        $_SESSION['user_name'] = $usuarioLogueado->nombre;
                        $_SESSION['user_role'] = $usuarioLogueado->rol;

                        // Redirige al usuario a la página principal
                        header("Location: index.php");
                        exit();
                    } else {
                        $this->errores['global'] = "Error al iniciar sesión automáticamente.";
                    }
                }
            }
        }

        return empty($this->errores);
    }
}
?>
