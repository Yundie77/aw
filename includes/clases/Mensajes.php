<?php
class Mensajes {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getMessagesByGroup($grupo_id) {
        $sql = "SELECT m.id, m.usuario_id, m.grupo_id, m.contenido, m.fecha, u.nombre AS usuario_nombre
                FROM mensajes m
                JOIN usuarios u ON m.usuario_id = u.id
                WHERE m.grupo_id = ?
                ORDER BY m.fecha ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $grupo_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $messages;
    }
    
    // Otros métodos para Mensajes...
}
?>