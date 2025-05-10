<?php
namespace es\ucm\fdi\aw;

class Mensajes {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function obtenerMensajesPorGrupo($id_grupo) {
        $sql = "SELECT m.id, m.usuario_id, m.grupo_id, m.contenido, m.fecha, u.nombre AS nombre_usuario
                FROM mensajes m
                JOIN usuarios u ON m.usuario_id = u.id
                WHERE m.grupo_id = ?
                ORDER BY m.fecha ASC";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Falló la preparación de obtenerMensajesPorGrupo: (" . $this->conn->errno . ") " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("i", $id_grupo);
        if (!$stmt->execute()) {
            error_log("Falló la ejecución de obtenerMensajesPorGrupo: (" . $stmt->errno . ") " . $stmt->error);
            $stmt->close();
            return [];
        }
        $resultado = $stmt->get_result();
        $mensajes = $resultado->fetch_all(MYSQLI_ASSOC);
        foreach ($mensajes as &$mensaje) {
            $mensaje['contenido'] = htmlspecialchars($mensaje['contenido'], ENT_QUOTES, 'UTF-8');
            $mensaje['nombre_usuario'] = htmlspecialchars($mensaje['nombre_usuario'], ENT_QUOTES, 'UTF-8');
        }
        $stmt->close();
        return $mensajes;
    }

    public function insertarMensaje($id_usuario, $id_grupo, $contenido) {
        $sql = "INSERT INTO mensajes (usuario_id, grupo_id, contenido, fecha) VALUES (?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Falló la preparación para insertarMensaje: (" . $this->conn->errno . ") " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("iis", $id_usuario, $id_grupo, $contenido);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            error_log("Falló la ejecución para insertarMensaje: (" . $stmt->errno . ") " . $stmt->error);
            $stmt->close();
            return false;
        }
    }
}
?>
