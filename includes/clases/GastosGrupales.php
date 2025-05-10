<?php
namespace es\ucm\fdi\aw;

class GastosGrupales {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getGastosPorParticipanteGrafico($grupo_id) {
    $stmt = $this->conn->prepare("
        SELECT u.nombre, IFNULL(SUM(g.monto), 0) AS total
        FROM usuarios u
        JOIN grupo_usuarios gu ON gu.usuario_id = u.id
        LEFT JOIN gastos_grupales g ON g.usuario_id = u.id AND g.grupo_id = gu.grupo_id
        WHERE gu.grupo_id = ?
        GROUP BY u.id, u.nombre
    ");
    $stmt->bind_param("i", $grupo_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $datos = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Si no hay datos, retornar estructura vacía
    if (empty($datos)) {
        return [
            'labels' => [],
            'datos' => []
        ];
    }

    $labels = [];
    $valores = [];

    foreach ($datos as $fila) {
        $labels[] = $fila['nombre'];
        $valores[] = (float)$fila['total'];
    }

    return [
        'labels' => $labels,
        'datos' => $valores
    ];
}

public function getNombreGrupo($grupo_id) {
    $stmt = $this->conn->prepare("SELECT nombre FROM grupos WHERE id = ?");
    $stmt->bind_param("i", $grupo_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $grupo = $resultado->fetch_assoc();
    $stmt->close();

    return $grupo ? $grupo['nombre'] : 'Desconocido';
}

public function getParticipantesConGastos($grupo_id) {
    $stmt = $this->conn->prepare("
        SELECT u.id, u.nombre, IFNULL(SUM(g.monto), 0) AS gasto
        FROM grupo_usuarios gu
        JOIN usuarios u ON gu.usuario_id = u.id
        LEFT JOIN gastos_grupales g ON g.usuario_id = u.id AND g.grupo_id = gu.grupo_id
        WHERE gu.grupo_id = ?
        GROUP BY u.id, u.nombre
    ");
    $stmt->bind_param("i", $grupo_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $participantes = [];
    while ($row = $res->fetch_assoc()) {
        $participantes[$row['id']] = [
            'nombre' => $row['nombre'],
            'gasto' => (float)$row['gasto']
        ];
    }

    $stmt->close();
    return $participantes;
}






    

}
?>
