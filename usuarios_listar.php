<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/clases/Usuario.php';

$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();
$usuarios = \es\ucm\fdi\aw\Usuario::getAll($conn);

echo "<table id='tablaUsuarios' class='user-table'>
<thead>
<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr>
</thead><tbody>";

foreach ($usuarios as $usuario) {
    $estado = $usuario->estado;
    //$bloqueado = $usuario->bloquearHasta;

    echo "<tr>
        <td>{$usuario->getId()}</td>
        <td>{$usuario->getNombre()}</td>
        <td>{$usuario->email}</td>
        <td>{$usuario->getRol()}</td>
        <td>$estado</td>
        <td class='acciones'>
            <button class='btn-toggle-estado' data-id='{$usuario->getId()}'>
                " . ($estado === 'activo' ? 'Desactivar' : 'Activar') . "
            </button>
            <button class='btn-bloquear' data-id='{$usuario->getId()}'>
                Bloquear 1h
            </button>
        </td>
    </tr>";
}
echo "</tbody></table>";
