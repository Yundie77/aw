<?php

require_once __DIR__ . '/Formulario.php';
require_once __DIR__ . '/clases/Usuario.php';

class FormularioLogin extends Formulario {
    public function __construct() {
        parent::__construct('formLogin', ['urlRedireccion' => 'index.php']);
    }
    
    protected $errores = []; 
    protected function generaCamposFormulario(&$datos) {
        ob_start();
        // Se muestran errores globales
        echo self::generaListaErroresGlobales($this->errores, 'errores');
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <label for="username">Usuario o correo electrónico</label>
                <input type="text" id="username" name="username" required placeholder="Ingrese su usuario o correo electrónico" 
                value="<?php echo htmlspecialchars($datos['username'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="Ingrese su contraseña">
            </div>
            <button type="submit" class="btn btn-green">Iniciar Sesión</button>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
        <?php
        return ob_get_clean();
    }

    protected function procesaFormulario(&$datos) {
        $username = trim($datos['username'] ?? '');
        $password = $datos['password'] ?? '';

        if ($username === '' || $password === '') {
            $this->errores['global'] = "Debe completar todos los campos.";
            return;
        }

        // Utiliza el método login de la clase Usuario
        $usuario = Usuario::login($username, $password);
        if ($usuario) {
            // Inicia sesión y redirige según el rol del usuario
            $_SESSION['user_id'] = $usuario->id;
            $_SESSION['user_name'] = $usuario->nombre;
            $_SESSION['user_role'] = $usuario->rol;
            header("Location: " . ($usuario->rol === 'admin' ? 'admin.php' : 'index.php'));
            exit();
        } else {
            $this->errores['global'] = "Usuario o contraseña incorrectos.";
        }
    }
}
?>
