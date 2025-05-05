<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Usuario;

class FormularioRegistro extends Formulario {
    public function __construct() {
        parent::__construct('formRegistro', ['urlRedireccion' => 'index.php']);
    }

    protected function generaCamposFormulario(&$datos) {
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $email = $datos['email'] ?? '';
        $password = $datos['password'] ?? '';
        $confirmPassword = $datos['confirm_password'] ?? '';

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores, 'error-message');
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'email', 'password', 'confirm_password'], $this->errores, 'span', ['class' => 'error-message']);

        ob_start();
        ?>
        <div class="login-container">
            <h1>Registro</h1>

            <?= $htmlErroresGlobales ?>

            <form id="formRegistro" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <div class="form-group">
                    <label for="nombreUsuario">Nombre de usuario</label>
                    <input type="text" id="campoUser" name="nombreUsuario" placeholder="Introduce tu nombre de usuario">
                    <span id="userOK">✔</span>
                    <span id="userMal">❌</span>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="campoEmail" name="email" placeholder="Introduce tu correo electrónico">
                    <span id="correoOK">✔</span>
                    <span id="correoMal">❌</span>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Introduce tu contraseña">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repite tu contraseña">
                </div>

                <button type="submit" class="btn btn-green">Registrar</button>
            </form>

            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </div>
        <?php
        return ob_get_clean();
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = trim($datos['email'] ?? '');
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = $datos['password'] ?? '';
        $confirmPassword = $datos['confirm_password'] ?? '';

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

        if (empty($this->errores)) {
            if (Usuario::buscaUsuario($nombreUsuario)) {
                $this->errores['nombreUsuario'] = "El nombre de usuario ya está registrado.";
            } else {
                $usuario = Usuario::crea($nombreUsuario, $email, $password, 'usuario');
                if (!$usuario) {
                    $this->errores[] = "Error al registrar el usuario.";
                } else {
                    $usuarioLogueado = Usuario::login($nombreUsuario, $password);
                    if ($usuarioLogueado) {
                        if (session_status() !== PHP_SESSION_ACTIVE) {
                            session_start();
                        }
                        $_SESSION['user_id'] = $usuarioLogueado->id;
                        $_SESSION['user_name'] = $usuarioLogueado->nombre;
                        $_SESSION['user_role'] = $usuarioLogueado->rol;

                        header("Location: index.php");
                        exit();
                    } else {
                        $this->errores[] = "Error al iniciar sesión automáticamente.";
                    }
                }
            }
        }

        return empty($this->errores);
    }
}
?>

<script type="text/javascript" src="js/validacionRegistro.js"></script>