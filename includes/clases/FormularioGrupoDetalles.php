<?php
namespace es\ucm\fdi\aw;

class FormularioGrupoDetalles {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerGrupo($group_id) {
        $stmt = $this->conn->prepare("SELECT * FROM grupos WHERE id = ?");
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerParticipantes($group_id) {
        $stmt = $this->conn->prepare("
            SELECT u.nombre, COALESCE(SUM(gm.monto), 0) AS total 
            FROM grupo_usuarios gu 
            INNER JOIN usuarios u ON gu.usuario_id = u.id 
            LEFT JOIN gastos_grupales gm ON gu.grupo_id = gm.grupo_id AND gu.usuario_id = gm.usuario_id 
            WHERE gu.grupo_id = ? 
            GROUP BY gu.usuario_id, u.nombre
        ");
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}