<?php
namespace es\ucm\fdi\aw;

class FormularioGrupoDetalles {
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

    // Generar el gráfico de participantes
    public function generarGrafico($participants) {
        // Preparar datos para el gráfico
        $chartLabels = json_encode(array_column($participants, 'nombre'));
        $chartData   = json_encode(array_column($participants, 'total'));

        return [$chartLabels, $chartData];
    }

    // Obtener los detalles completos para el grupo
    public function obtenerDetallesGrupo($group_id) {
        $grupo = $this->obtenerGrupo($group_id);
        $participantes = $this->obtenerParticipantes($group_id);
        $grafico = $this->generarGrafico($participantes);
        
        return [
            'grupo' => $grupo,
            'participantes' => $participantes,
            'grafico' => $grafico
        ];
    }
}
?>
