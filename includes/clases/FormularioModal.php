<?php
namespace es\ucm\fdi\aw;

class FormularioModal
{
    private $conn;

    // Constructor para inicializar la conexión a la base de datos
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Método para procesar las acciones de los formularios (agregar grupo, miembro, modificar, eliminar)
    public function procesarAccion($accion)
    {
        switch ($accion) {
            case 'agregar_grupo':
                $nombre = trim($_POST['nombre'] ?? '');
                $objetivo = trim($_POST['objetivo'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                return $this->agregarGrupo($nombre, $objetivo, $descripcion);
                break;

            case 'agregar_miembro':
                $grupo_id = (int) ($_POST['grupo_id'] ?? 0);
                $usuario_id = (int) ($_POST['usuario_id'] ?? 0);
                $monto = trim($_POST['monto'] ?? '');
                return $this->agregarMiembro($grupo_id, $usuario_id, $monto);
                break;

            case 'modificar_grupo':
                $grupo_id = (int) ($_POST['grupo_id'] ?? 0);
                $nombre = trim($_POST['nombre'] ?? '');
                $objetivo = trim($_POST['objetivo'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                return $this->modificarGrupo($grupo_id, $nombre, $objetivo, $descripcion);
                break;

            case 'eliminar_grupo':
                $grupo_id = (int) ($_POST['grupo_id'] ?? 0);
                return $this->eliminarGrupo($grupo_id);
                break;

            default:
                return ['success' => false, 'error' => 'Acción no válida.'];
        }
    }
}
?>