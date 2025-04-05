<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Aplicacion;
class Categorias {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public static function getAll() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT id, nombre FROM categorias ORDER BY nombre ASC";
        $result = $conn->query($sql);
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