<?php
namespace es\ucm\fdi\aw;

class FormularioGrupos {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerGrupos() {
        $query = "SELECT g.*, (SELECT COUNT(*) FROM grupo_usuarios WHERE grupo_id = g.id) AS participantes FROM grupos g";
        $result = $this->conn->query($query);
        if (!$result) {
            die("Error en la consulta: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}