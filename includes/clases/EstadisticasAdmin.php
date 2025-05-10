<?php
namespace es\ucm\fdi\aw;

class EstadisticasAdmin {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function nuevosUsuariosPorMes() {
        $query = "SELECT DATE_FORMAT(fecha_creacion, '%Y-%m') AS mes, COUNT(*) AS total
                  FROM usuarios GROUP BY mes ORDER BY mes ASC";
        return $this->fetchAllAssoc($query);
    }

    public function gastoPorCategoria() {
        $query = "SELECT c.nombre AS categoria, SUM(g.monto) AS total_categoria
                  FROM categorias c JOIN gastos g ON c.id = g.categoria_id
                  GROUP BY c.nombre ORDER BY total_categoria DESC";
        return $this->fetchAllAssoc($query);
    }

    public function usuariosPorEstado() {
        $query = "SELECT estado, COUNT(*) AS total FROM usuarios GROUP BY estado";
        $result = $this->fetchAllAssoc($query);
        return array_map(fn($r) => [
            'estado' => ucfirst($r['estado']),
            'total' => (int)$r['total']
        ], $result);
    }

    public function usuariosPorRol() {
        $query = "SELECT rol, COUNT(*) AS total FROM usuarios GROUP BY rol";
        $result = $this->fetchAllAssoc($query);
        return array_map(fn($r) => [
            'rol' => ucfirst($r['rol']),
            'total' => (int)$r['total']
        ], $result);
    }

    private function fetchAllAssoc($query) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(\MYSQLI_ASSOC);
    }
}
