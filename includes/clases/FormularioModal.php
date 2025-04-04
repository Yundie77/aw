<?php
namespace es\ucm\fdi\aw;

session_start();

require_once __DIR__ . '/../config.php';
use es\ucm\fdi\aw\Formulario; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    exit;
}

$accion = $_GET['accion'] ?? '';
$form = new Formulario($conn);

switch ($accion) {
    case 'agregar_grupo':
        $nombre = trim($_POST['nombre'] ?? '');
        $objetivo = trim($_POST['objetivo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        echo json_encode($form->agregarGrupo($nombre, $objetivo, $descripcion));
        break;
    case 'agregar_miembro':
        $grupo_id = (int) ($_POST['grupo_id'] ?? 0);
        $usuario_id = (int) ($_POST['usuario_id'] ?? 0);
        $monto = trim($_POST['monto'] ?? '');
        echo json_encode($form->agregarMiembro($grupo_id, $usuario_id, $monto));
        break;
    case 'modificar_grupo':
        $grupo_id = (int) ($_POST['grupo_id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $objetivo = trim($_POST['objetivo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        echo json_encode($form->modificarGrupo($grupo_id, $nombre, $objetivo, $descripcion));
        break;
    case 'eliminar_grupo':
        $grupo_id = (int) ($_POST['grupo_id'] ?? 0);
        echo json_encode($form->eliminarGrupo($grupo_id));
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Acción no válida.']);
        break;
}
exit;
?>