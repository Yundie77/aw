<?php
namespace es\ucm\fdi\aw;

class Admin {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function buscarUsuarios($criterio) {
        $sql = "SELECT id, nombre, email, rol FROM usuarios WHERE nombre LIKE ? OR email LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $criterio = '%' . $criterio . '%';
        $stmt->bind_param("ss", $criterio, $criterio);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $usuarios;
    }

    public function obtenerTodosUsuarios() {
        $sql = "SELECT id, nombre, email, rol FROM usuarios";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function cambiarRolUsuario($id, $nuevoRol) {
        $sql = "UPDATE usuarios SET rol = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $nuevoRol, $id);
        $stmt->execute();
        $stmt->close();
        return $stmt->affected_rows > 0;
    }
}
?>
