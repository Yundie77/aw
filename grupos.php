<?php
require_once 'includes/config.php';
require_once 'includes/clases/FormularioGrupos.php';

// Siempre al principio
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Redirección si no está autenticado
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para acceder a esta funcionalidad.";
    header("Location: login.php");
    exit();
}

// Conexión a la BD
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

// Mostrar mensajes flash de sesión
if (isset($_SESSION['mensaje_error'])) {
    echo '<div class="mensaje-error">' . htmlspecialchars($_SESSION['mensaje_error']) . '</div>';
    unset($_SESSION['mensaje_error']);
}

if (isset($_SESSION['mensaje_exito'])) {
    echo '<div class="mensaje-exito">' . htmlspecialchars($_SESSION['mensaje_exito']) . '</div>';
    unset($_SESSION['mensaje_exito']);
}

// Iniciar buffer de salida
ob_start();

// Título
echo '<h1 class="titulo-graficos">Grupos</h1>';

// Generar contenido desde el formulario
$formularioGrupos = new \es\ucm\fdi\aw\FormularioGrupos($conn);
echo $formularioGrupos->generarListaGrupos();
echo $formularioGrupos->generarBotones();
echo $formularioGrupos->generarModales();

// Mensajes GET (mejor si se pasa por sesión, pero aquí se mantiene)
if (isset($_GET['mensaje'])) {
    $mensajes = [
        'miembro_agregado' => "Miembro agregado correctamente al grupo.",
        'usuario_ya_miembro' => "El usuario ya es miembro del grupo.",
        'usuario_no_encontrado' => "Usuario no encontrado.",
        'grupo_no_encontrado' => "Grupo no encontrado.",
        'parametros_invalidos' => "Parámetros incompletos o inválidos.",
        'error_agregar_miembro' => "Error al agregar el miembro al grupo."
    ];
    if (array_key_exists($_GET['mensaje'], $mensajes)) {
        $color = ($_GET['mensaje'] === 'miembro_agregado') ? 'green' : 'red';
        echo "<p style='color:{$color};'>" . htmlspecialchars($mensajes[$_GET['mensaje']]) . "</p>";
    }
}

// Captura y plantilla
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Grupos";
require __DIR__ . '/includes/vistas/plantilla/plantilla.php';
