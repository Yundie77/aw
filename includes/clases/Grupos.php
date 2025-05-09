<?php
class Grupos {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener los detalles de un grupo por ID
    public function obtenerGrupo($group_id) {
        $stmt = $this->conn->prepare("SELECT * FROM grupos WHERE id = ?");
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Obtener los participantes y sus gastos
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

    // Obtener los gastos por categoría
    public function obtenerGastosPorCategoria($group_id) {
        $stmt = $this->conn->prepare("
            SELECT c.nombre AS categoria, SUM(gm.monto) AS total
            FROM gastos_grupales gm 
            INNER JOIN categorias c ON gm.categoria_id = c.id 
            WHERE gm.grupo_id = ? 
            GROUP BY gm.categoria_id
        ");
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener los gastos por participante
    public function obtenerGastosPorParticipante($group_id) {
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
    
    public function obtenerGruposPorUsuarioId($id_usuario) {
        $sql = "SELECT g.id, g.nombre 
                FROM grupos g
                JOIN grupo_usuarios gu ON g.id = gu.grupo_id
                WHERE gu.usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la consulta para obtener grupos por usuario: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("i", $id_usuario);
        if (!$stmt->execute()) {
            error_log("Error al ejecutar la consulta para obtener grupos por usuario: " . $stmt->error);
            $stmt->close();
            return [];
        }
        $resultado = $stmt->get_result();
        $grupos = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $grupos;
    }
}
?>
