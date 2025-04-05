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

    public function insertarGasto($user_id, $tipo, $categoria_id, $monto, $fecha, $comentario) {
        $sql = "INSERT INTO gastos (usuario_id, tipo, categoria_id, monto, fecha, comentario) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isidss", $user_id, $tipo, $categoria_id, $monto, $fecha, $comentario);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getGastoById($id, $user_id) {
        $sql = "SELECT * FROM gastos WHERE id = ? AND usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $gasto = $result->fetch_assoc();
        $result->free(); // Liberar el resultado
        $stmt->close();
        return $gasto;
    }

    public function actualizarGasto($id, $user_id, $tipo, $monto, $fecha, $comentario) {
        $sql = "UPDATE gastos SET tipo = ?, monto = ?, fecha = ?, comentario = ? WHERE id = ? AND usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sissii", $tipo, $monto, $fecha, $comentario, $id, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getFilteredMovimientos($user_id, $tipoFilter, $categoriaFilter, $search, $orderBy, $limit) {
        $conditions = "g.usuario_id = ?";
        $params = [$user_id];
        $types = "i";

        if ($tipoFilter && in_array($tipoFilter, ['Ingreso', 'Gasto'])) {
            $conditions .= " AND g.tipo = ?";
            $params[] = $tipoFilter;
            $types .= "s";
        }

        if ($categoriaFilter) {
            $conditions .= " AND c.nombre = ?";
            $params[] = $categoriaFilter;
            $types .= "s";
        }

        if ($search) {
            $conditions .= " AND (g.comentario LIKE ? OR c.nombre LIKE ?)";
            $searchParam = "%" . $search . "%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $types .= "ss";
        }

        $sql = "
            SELECT g.id, g.tipo, g.monto, g.fecha, g.comentario, c.nombre AS categoria
            FROM gastos g
            JOIN categorias c ON g.categoria_id = c.id
            WHERE $conditions
            ORDER BY g.fecha $orderBy, g.id $orderBy
            LIMIT ?
        ";
        $params[] = $limit;
        $types .= "i";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $movimientos = $result->fetch_all(MYSQLI_ASSOC);
        $result->free(); // Liberar el resultado
        $stmt->close();

        return $movimientos;
    }

    public function getTipos($user_id) {
        $sql = "SELECT DISTINCT tipo FROM gastos WHERE usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $tiposArray = [];
        while ($row = $result->fetch_assoc()) {
            $tiposArray[] = $row['tipo'];
        }
        $result->free(); // Liberar el resultado
        $stmt->close();
        return $tiposArray;
    }

}
?>
