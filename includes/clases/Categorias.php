<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Aplicacion;

class Categorias {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAll() {
        $sql = "SELECT DISTINCT nombre FROM categorias ORDER BY nombre ASC";
        $result = $this->conn->query($sql);
        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        $result->free();
        return $categorias;
    }

    public static function create($nombre) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "INSERT INTO categorias (nombre) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nombre);
        if ($stmt->execute()) {
            $id = $conn->insert_id;
            $stmt->close();
            return $id;
        }
        $stmt->close();
        return false;
    }
}
?>