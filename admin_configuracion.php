<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/includes/config.php';


if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: " . RUTA_APP . "login.php");
    exit();
}

$config = include(__DIR__ . '/includes/config_app.php');
$modoMantenimiento = $config['maintenance_mode'] ? 'Activado' : 'Desactivado';

ob_start();
?>

<div class="admin-container">
    <h2> Configuración del sitio</h2>
    
    <div class="card">
        <p><strong>Modo mantenimiento actual:</strong> 
            <span id="estado-mantenimiento" class="estado <?= $modoMantenimiento === 'Activado' ? 'activo' : 'inactivo' ?>">
                <?= $modoMantenimiento ?>
            </span>
        </p>
        <button id="toggleMantenimiento"
                class="boton-toggle <?= $modoMantenimiento === 'Activado' ? 'rojo' : 'verde' ?>">
            <?= $modoMantenimiento === 'Activado' ? 'Desactivar' : 'Activar' ?>
        </button>
    </div>
</div>

<!--  Notificación flotante -->
<div id="notificacion" class="notificacion"></div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function mostrarNotificacion(mensaje) {
        const $noti = $('#notificacion');
        $noti.text(mensaje).addClass('mostrar');
        setTimeout(() => $noti.removeClass('mostrar'), 3000);
    }

    $('#toggleMantenimiento').on('click', function () {
      $.post('cambiar_mantenimiento.php', {}, function (data) {
            if (data && data.estado) {
                const estado = data.estado;
                const $estado = $('#estado-mantenimiento');
                const $boton = $('#toggleMantenimiento');

                $estado.text(estado);
                $estado.removeClass('activo inactivo')
                       .addClass(estado === 'Activado' ? 'activo' : 'inactivo');

                if (estado === 'Activado') {
                    $boton.text('Desactivar')
                          .removeClass('verde').addClass('rojo');
                    mostrarNotificacion('Modo mantenimiento activado');
                } else {
                    $boton.text('Activar')
                          .removeClass('rojo').addClass('verde');
                    mostrarNotificacion('Modo mantenimiento desactivado');
                }
            } else {
                alert('Respuesta inesperada del servidor.');
            }
        }, 'json').fail(function (jqXHR, textStatus, errorThrown) {
            console.error('Error en AJAX:', textStatus, errorThrown);
            alert('Hubo un error al intentar cambiar el estado.');
        });
    });
</script>

<?php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Configuración del Sitio";
require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';
?>
