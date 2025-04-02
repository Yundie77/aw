<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

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
        $this->errores = [];
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();

        $username = trim($datos['username'] ?? '');
        $password = $datos['password'] ?? '';

        if ($username === '' || $password === '') {
            $this->errores['global'] = "Debe completar todos los campos.";
            return;
        }

        $stmt = $conn->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = ? OR nombre = ?");
        if (!$stmt) {
            $this->errores['global'] = "Error en la consulta";
            return;
        }
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $nombre, $stored_password, $rol);
            $stmt->fetch();
            if (password_verify($password, $stored_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $nombre;
                $_SESSION['user_role'] = $rol;
                header("Location: " . ($rol === 'admin' ? 'admin.php' : 'index.php'));
                exit();
            } else {
                $this->errores['password'] = "Contraseña incorrecta";
            }
        } else {
            $this->errores['username'] = "Usuario no encontrado";
        }
        $stmt->close();
    }
}
?>
