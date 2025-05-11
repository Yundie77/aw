<?php
class Grupos {
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

    // Obtener los gastos por categoría
    public function obtenerGastosPorCategoria($group_id) {
        $stmt = $this->conn->prepare("
            SELECT c.nombre AS categoria, SUM(gm.monto) AS total
            FROM gastos_grupales gm 
            INNER JOIN categorias c ON gm.categoria_id = c.id 
            WHERE gm.grupo_id = ? 
            GROUP BY gm.categoria_id
        ");
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener los gastos por participante
    public function obtenerGastosPorParticipante($group_id) {
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
    
    public function obtenerGruposPorUsuarioId($id_usuario) {
        $sql = "SELECT g.id, g.nombre 
                FROM grupos g
                JOIN grupo_usuarios gu ON g.id = gu.grupo_id
                WHERE gu.usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la consulta para obtener grupos por usuario: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("i", $id_usuario);
        if (!$stmt->execute()) {
            error_log("Error al ejecutar la consulta para obtener grupos por usuario: " . $stmt->error);
            $stmt->close();
            return [];
        }
        $resultado = $stmt->get_result();
        $grupos = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $grupos;
    }

    public function actualizarGrupo($grupo_id, $nombre, $objetivo) {
    $stmt = $this->conn->prepare("UPDATE grupos SET nombre = ?, objetivo = ? WHERE id = ?");
    if (!$stmt) {
        throw new \Exception("Error al preparar la consulta: " . $this->conn->error);
    }

    $stmt->bind_param("sii", $nombre, $objetivo, $grupo_id);
    $ok = $stmt->execute();
    $stmt->close();

    return $ok;
}

public function eliminarGrupo($grupo_id) {
    $stmt = $this->conn->prepare("DELETE FROM grupos WHERE id = ?");
    if (!$stmt) {
        throw new \Exception("Error al preparar la consulta: " . $this->conn->error);
    }

    $stmt->bind_param("i", $grupo_id);
    $ok = $stmt->execute();
    $stmt->close();

    return $ok;
}

public function agregarMiembro($grupoId, $usuarioId, $rol = 'miembro', $accionPorUsuarioId = null) {
    // Verificar si ya es miembro
    $stmt = $this->conn->prepare("SELECT 1 FROM grupo_usuarios WHERE grupo_id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $grupoId, $usuarioId);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        return ['error' => 'El usuario ya es miembro de este grupo.'];
    }
    $stmt->close();

    // Verificar si el que agrega es admin del grupo si está agregando otro admin
    if ($rol === 'admin_grupo' && $accionPorUsuarioId !== null) {
        $stmt = $this->conn->prepare("
            SELECT 1 FROM grupo_usuarios 
            WHERE grupo_id = ? AND usuario_id = ? AND rol_grupo = 'admin_grupo'
        ");
        $stmt->bind_param("ii", $grupoId, $accionPorUsuarioId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            $stmt->close();
            return ['error' => 'No tienes permiso para agregar un admin al grupo.'];
        }
        $stmt->close();
    }

    // Insertar miembro
    $stmt = $this->conn->prepare("
        INSERT INTO grupo_usuarios (grupo_id, usuario_id, rol_grupo) VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iis", $grupoId, $usuarioId, $rol);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        return ['success' => 'Miembro agregado correctamente al grupo.'];
    } else {
        return ['error' => 'Error al agregar el miembro.'];
    }
}

public function crearGrupo($nombre, $objetivo, $usuarioCreadorId) {
    // Validación básica
    if (empty($nombre) || empty($objetivo)) {
        return ['error' => 'Todos los campos son obligatorios.'];
    }

    // Verificar si ya existe un grupo con ese nombre
    $stmtCheck = $this->conn->prepare("SELECT id FROM grupos WHERE nombre = ?");
    $stmtCheck->bind_param("s", $nombre);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        $stmtCheck->close();
        return ['error' => 'Ya existe un grupo con este nombre.'];
    }
    $stmtCheck->close();

    // Crear el grupo
    $stmt = $this->conn->prepare("INSERT INTO grupos (nombre, objetivo) VALUES (?, ?)");
    if (!$stmt) {
        throw new \Exception("Error al preparar la inserción: " . $this->conn->error);
    }
    $stmt->bind_param("ss", $nombre, $objetivo);

    if (!$stmt->execute()) {
        $stmt->close();
        return ['error' => 'Error al crear el grupo: ' . $stmt->error];
    }

    $grupoId = $stmt->insert_id;
    $stmt->close();

    // Insertar al creador como admin
    $stmt2 = $this->conn->prepare("INSERT INTO grupo_usuarios (grupo_id, usuario_id, rol_grupo) VALUES (?, ?, 'admin_grupo')");
    $stmt2->bind_param("ii", $grupoId, $usuarioCreadorId);
    $stmt2->execute();
    $stmt2->close();

    return ['success' => 'Grupo agregado correctamente.'];
}

public function obtenerTodosGrupos() {
    $sql = "SELECT id, nombre FROM grupos";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        error_log("Error al preparar la consulta para obtener todos los grupos: " . $this->conn->error);
        return [];
    }
    if (!$stmt->execute()) {
        error_log("Error al ejecutar la consulta para obtener todos los grupos: " . $stmt->error);
        $stmt->close();
        return [];
    }
    $resultado = $stmt->get_result();
    $grupos = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $grupos;
}


}
?>
