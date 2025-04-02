<?php
class GrupoUsuarios {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getMembers($grupo_id) {
        $sql = "SELECT u.id, u.nombre, u.email, gu.rol_grupo
                FROM grupo_usuarios gu
                JOIN usuarios u ON gu.usuario_id = u.id
                WHERE gu.grupo_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $grupo_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $members = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $members;
    }
    
    // Otros métodos para GrupoUsuarios...
}
?>