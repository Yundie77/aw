<?php
namespace es\ucm\fdi\aw;

class Gastos {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getTotalIngresos($user_id) {
        $sql = "SELECT IFNULL(SUM(monto), 0) AS total_ingresos FROM gastos WHERE usuario_id = ? AND tipo = 'Ingreso'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $result->free();
        $stmt->close();
        return $row['total_ingresos'];
    }
    
    public function getTotalGastos($user_id) {
        $sql = "SELECT IFNULL(SUM(monto), 0) AS total_gastos FROM gastos WHERE usuario_id = ? AND tipo = 'Gasto'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $result->free();
        $stmt->close();
        return $row['total_gastos'];
    }
    
    public function getIngresosMes($user_id) {
        $sql = "SELECT IFNULL(SUM(monto), 0) AS ingresos_mes FROM gastos WHERE usuario_id = ? AND tipo = 'Ingreso' AND MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $result->free();
        $stmt->close();
        return $row['ingresos_mes'];
    }
    
    public function getGastosMes($user_id) {
        $sql = "SELECT IFNULL(SUM(monto), 0) AS gastos_mes FROM gastos WHERE usuario_id = ? AND tipo = 'Gasto' AND MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $result->free();
        $stmt->close();
        return $row['gastos_mes'];
    }
    
    public function getUltimosMovimientos($user_id, $limit = 5) {
        $sql = "SELECT g.tipo, g.monto, g.fecha, g.comentario, c.nombre AS categoria
                FROM gastos g JOIN categorias c ON g.categoria_id = c.id
                WHERE g.usuario_id = ?
                ORDER BY g.fecha DESC, g.id DESC
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $movimientos = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        $stmt->close();
        return $movimientos;
    }
    
    public function getDonutData($user_id) {
        $sql = "SELECT c.nombre AS categoria, SUM(g.monto) AS total_categoria
                FROM gastos g JOIN categorias c ON g.categoria_id = c.id
                WHERE g.usuario_id = ? AND g.tipo = 'Gasto'
                GROUP BY g.categoria_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $donutData = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        $stmt->close();
        return $donutData;
    }
    
    public function getBarData($user_id) {
        $sql = "SELECT YEAR(fecha) AS anio, MONTH(fecha) AS mes,
                       SUM(CASE WHEN tipo = 'Ingreso' THEN monto ELSE 0 END) AS total_ingreso,
                       SUM(CASE WHEN tipo = 'Gasto' THEN monto ELSE 0 END) AS total_gasto
                FROM gastos
                WHERE usuario_id = ?
                GROUP BY YEAR(fecha), MONTH(fecha)
                ORDER BY YEAR(fecha), MONTH(fecha)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $barData = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        $stmt->close();
        return $barData;
    }
    // ...otros métodos para gastos si es necesario...
}
?>
