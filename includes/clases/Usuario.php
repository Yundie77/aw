<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Aplicacion;

class Usuario {
    // Propiedades que corresponden a los campos de la tabla 'usuarios'
    public $id;
    public $nombre;    // Nombre de usuario (login)
    public $email;
    public $password;  // Almacenará el hash de la contraseña
    public $rol;

    // Constructor: crea un objeto Usuario a partir de los datos de la BD
    public function __construct($id, $nombre, $email, $password, $rol) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
    }

    /**
     * Busca en la base de datos un usuario cuyo nombre (login) coincida con $nombreUsuario.
     * Retorna un objeto Usuario si lo encuentra o false en caso contrario.
     */
    public static function buscaUsuario($nombreUsuario) {
        // Se asume la existencia de una clase Aplicacion que gestiona la conexión a la BD.
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre = ?");
        if (!$stmt) {
            throw new Exception("Error en prepare: " . $conn->error);
        }
        $stmt->bind_param("s", $nombreUsuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return new Usuario($row['id'], $row['nombre'], $row['email'], $row['password'], $row['rol']);
        }
        return false;
    }

    /**
     * Comprueba si la contraseña introducida ($password) coincide con el hash almacenado.
     * Se utiliza password_verify para comparar.
     */
    public function compruebaPassword($password) {
        return password_verify($password, $this->password);
    }

    /**
     * Realiza el proceso de login: busca el usuario y, si lo encuentra, verifica la contraseña.
     * Retorna un objeto Usuario si el login es correcto, o false en caso contrario.
     */
    public static function login($nombreUsuario, $password) {
        $usuario = self::buscaUsuario($nombreUsuario);
        if ($usuario && $usuario->compruebaPassword($password)) {
            return $usuario;
        }
        return false;
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     * Parámetros:
     *   - $nombreUsuario: se guarda en el campo 'nombre' (usado para el login)
     *   - $nombre: en este ejemplo lo usaremos para el email (ya que la tabla 'usuarios' tiene 'nombre' y 'email')
     *   - $password: la contraseña en texto plano (se almacenará de forma segura usando password_hash)
     *   - $rol: rol del usuario (por ejemplo, 'usuario' o 'admin')
     *
     * Retorna un objeto Usuario con el usuario recién creado.
     */
    public static function crea($nombreUsuario, $nombre, $password, $rol) {
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();

        // Crear el hash de la contraseña
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error en prepare: " . $conn->error);
        }
        $stmt->bind_param("ssss", $nombreUsuario, $nombre, $hash, $rol);
        try {
            $stmt->execute();
        } catch(mysqli_sql_exception $e) {
            // Si se viola la restricción UNIQUE (por ejemplo, ya existe el usuario)
            if ($conn->sqlstate == 23000) {
                throw new Exception("El usuario '$nombreUsuario' ya existe.");
            }
            throw $e;
        }
        $id = $stmt->insert_id;
        return new Usuario($id, $nombreUsuario, $nombre, $hash, $rol);
    }
}
?>
