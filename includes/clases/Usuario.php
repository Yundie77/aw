<?php
namespace es\ucm\fdi\aw;

class Usuario {
    private $id;
    private $nombre;
    private $email;
    private $rol;

    public function __construct($id, $nombre, $email, $rol) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->rol = $rol;
    }

    public static function buscaUsuario($conn, $email) {
        $id = $nombre = $rol = null;
        $stmt = $conn->prepare("SELECT id, nombre, email, rol FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $nombre, $email, $rol);
        if ($stmt->fetch()) {
            return new Usuario($id, $nombre, $email, $rol);
        }
        return null;
    }

    public static function creaUsuario($conn, $nombre, $email, $password, $rol = 'usuario') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Encriptar contraseña
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $email, $hashedPassword, $rol);
        return $stmt->execute();
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRol() {
        return $this->rol;
    }
}
