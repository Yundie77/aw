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
    public $estado; //activo, inactivo, bloqueado
    public $bloqueado_hasta;
    public $fecha_creacion;


    // Constructor: crea un objeto Usuario a partir de los datos de la BD
    public function __construct($id, $nombre, $email, $password, $rol, $estado, $bloqueadoHasta, $fechaCreacion) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
        $this->estado = $estado;
        $this->bloqueado_hasta = $bloqueadoHasta;
        $this->fecha_creacion = $fechaCreacion;
    }
    

    public function getId() {
        return $this->id;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getRol() {
        return $this->rol;
    }
    

    /**
     * Busca en la base de datos un usuario cuyo nombre (login) coincida con $nombreUsuario.
     * Retorna un objeto Usuario si lo encuentra o false en caso contrario.
     */
    public static function buscaUsuario($nombreUsuario) {
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $nombreUsuario = $conn->real_escape_string($nombreUsuario);  // Escapar parámetro
    
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre = ?");
        if (!$stmt) {
            throw new \Exception("Error en prepare: " . $conn->error);
        }
    
        $stmt->bind_param("s", $nombreUsuario);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $usuario = false;
    
        if ($row = $result->fetch_assoc()) {
            $usuario = new Usuario(
                $row['id'],
                $row['nombre'],
                $row['email'],
                $row['password'],
                $row['rol'],
                $row['estado'],
                $row['bloqueado_hasta'],
                $row['fecha_creacion']
            );
        }
    
        $result->free();   // Libera el resultado
        $stmt->close();    //  Cierra el statement 
    
        return $usuario;
    }
    
    /**
     * Busca en la base de datos un usuario cuyo correo coincida con $email.
     * Retorna un objeto Usuario si lo encuentra o false en caso contrario.
     */
    public static function buscaCorreo($email) {
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        $email = $conn->real_escape_string($email);  // Escapar parámetro

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        if (!$stmt) {
            throw new \Exception("Error en prepare: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $usuario = false;
        if ($row = $result->fetch_assoc()) {
            $usuario = new Usuario(
                $row['id'],
                $row['nombre'],
                $row['email'],
                $row['password'],
                $row['rol'],
                $row['estado'],
                $row['bloqueado_hasta'],
                $row['fecha_creacion']
            );
        }
        
        $result->free();
        $stmt->close();
        
        return $usuario;
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

        // Escapar parámetros de entrada
        $nombreUsuario = $conn->real_escape_string($nombreUsuario);
        $nombre = $conn->real_escape_string($nombre);
        $rol = $conn->real_escape_string($rol);

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
        $stmt->close();

        // Asignar categorías por defecto al nuevo usuario
        $categoriasPorDefecto = [
            'Compra', 'Comida', 'Ocio', 'Ropa', 'Salud', 
            'Transporte', 'Otros', 'Salario', 'Deporte'
        ];
        $stmt = $conn->prepare("INSERT INTO categorias (nombre, usuario_id) VALUES (?, ?)");
        foreach ($categoriasPorDefecto as $categoria) {
            $stmt->bind_param("si", $categoria, $id);
            $stmt->execute();
        }
        $stmt->close();

        return new Usuario(
            $id,
            $nombreUsuario,
            $nombre,
            $hash,
            $rol,
            'activo',           // estado por defecto
            null,               // bloqueado_hasta
            date('Y-m-d H:i:s') // fecha_creacion actual (opcional, para mantener consistencia)
        );
    }

    public static function getAll($conn) {
        $usuarios = [];
        $query = "SELECT * FROM usuarios";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = new Usuario(
                $row['id'],
                $row['nombre'],
                $row['email'],
                $row['password'],
                $row['rol'],
                $row['estado'],
                $row['bloqueado_hasta'],
                $row['fecha_creacion']
            );
                    }
    
        $stmt->close();
        return $usuarios;
    }

    public static function eliminarPorId($conn, $id) {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public static function buscaPorId($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            return new Usuario(
                $row['id'], $row['nombre'], $row['email'], $row['password'], $row['rol'],
                $row['estado'], $row['bloqueado_hasta'], $row['fecha_creacion']
            );
        }
        return null;
    }
    
    public static function actualizaEstado($conn, $id, $estado) {
        $stmt = $conn->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
        $stmt->bind_param("si", $estado, $id);
        return $stmt->execute();
    }
    
    public static function bloquearHasta($conn, $id, $fecha) {
        $stmt = $conn->prepare("UPDATE usuarios SET estado = 'bloqueado', bloqueado_hasta = ? WHERE id = ?");
        $stmt->bind_param("si", $fecha, $id);
        return $stmt->execute();
    }
    
    
    
}
?>
