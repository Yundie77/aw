<?php
class Categorias {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Devuelve todas las categorías
    public function getAll() {
        $sql = "SELECT * FROM categorias";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $categorias = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $categorias;
    }
    
    // Otros métodos para Categorias...
}
?>