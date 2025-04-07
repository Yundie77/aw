<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Aplicacion;
class Categorias {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public static function getAll($usuario_id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT id, nombre FROM categorias WHERE usuario_id = ? ORDER BY nombre ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        $stmt->close();
        return $categorias;
    }

    public static function create($nombre, $usuario_id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "INSERT INTO categorias (nombre, usuario_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nombre, $usuario_id);
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