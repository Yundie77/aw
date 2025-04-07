<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Usuario;

class FormularioLogin extends Formulario {
    public function __construct() {
        parent::__construct('formLogin', ['urlRedireccion' => 'index.php']);
    }

    protected function generaCamposFormulario(&$datos) {
        $nombreUsuario = $datos['nombreUsuario'] ?? '';

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores, 'error-message');
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'password'], $this->errores, 'span', ['class' => 'error-message']);

        ob_start();
        ?>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="login-container">
            <h2>Iniciar Sesión</h2>
            <?= $htmlErroresGlobales ?>

            <div class="form-group">
                <label for="nombreUsuario">Nombre de usuario</label>
                <input type="text" id="nombreUsuario" name="nombreUsuario"
                       placeholder="Introduce tu nombre de usuario"
                       value="<?= htmlspecialchars($nombreUsuario) ?>">
                <?= $erroresCampos['nombreUsuario'] ?? '' ?>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Introduce tu contraseña">
                <?= $erroresCampos['password'] ?? '' ?>
            </div>

            <button type="submit" class="btn btn-green">Iniciar Sesión</button>
            <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </form>
        <?php
        return ob_get_clean();
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$nombreUsuario || $nombreUsuario === '') {
            $this->errores['nombreUsuario'] = 'El nombre de usuario no puede estar vacío.';
        }

        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$password || $password === '') {
            $this->errores['password'] = 'La contraseña no puede estar vacía.';
        }

        if (empty($this->errores)) {
            $usuario = Usuario::login($nombreUsuario, $password);
            if (!$usuario) {
                $this->errores[] = 'Usuario o contraseña incorrectos.';
            } else {
                $_SESSION['login'] = true;
                $_SESSION['nombre'] = $usuario->getNombre();
                $_SESSION['esAdmin'] = $usuario->tieneRol(Usuario::ADMIN_ROLE);
            }
        }
    }
}
?>
