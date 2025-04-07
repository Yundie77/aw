<link rel="stylesheet" type="text/css" href="styles.css" />

<?php
// Iniciamos la sesión
session_start();


// Mostrar mensaje de error si está en la sesión
if (isset($_SESSION['mensaje_error'])) {
    echo '<div class="mensaje-error">' . $_SESSION['mensaje_error'] . '</div>';
    unset($_SESSION['mensaje_error']); // Limpiar el mensaje después de mostrarlo
}

// Mostrar mensaje de éxito, si existe
if (isset($_SESSION['mensaje_exito'])) {
    echo '<div class="mensaje-exito">' . $_SESSION['mensaje_exito'] . '</div>';
    unset($_SESSION['mensaje_exito']); // Limpiar el mensaje después de mostrarlo
}

// Incluimos la configuración y el formulario de grupos
require_once 'includes/config.php';
require_once 'includes/clases/FormularioGrupos.php';

// Obtenemos la instancia de la aplicación y la conexión a la base de datos
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para acceder a esta funcionalidad.";
    header("Location: login.php");
    exit();
}

// Creamos una instancia del formulario de grupos
$formularioGrupos = new \es\ucm\fdi\aw\FormularioGrupos($conn);

// Iniciamos el buffer de salida
ob_start();

// Generamos el contenido principal utilizando los métodos del formulario
echo $formularioGrupos->generarListaGrupos(); // Genera la lista de grupos
echo $formularioGrupos->generarBotones();    // Genera los botones para las ventanas modales
echo $formularioGrupos->generarModales();    // Genera las ventanas modales

if (isset($_GET['mensaje'])) {
    switch ($_GET['mensaje']) {
        case 'miembro_agregado':
            echo "<p style='color:green;'>Miembro agregado correctamente al grupo.</p>";
            break;
        case 'usuario_ya_miembro':
            echo "<p style='color:red;'>El usuario ya es miembro del grupo.</p>";
            break;
        case 'usuario_no_encontrado':
            echo "<p style='color:red;'>Usuario no encontrado.</p>";
            break;
        case 'grupo_no_encontrado':
            echo "<p style='color:red;'>Grupo no encontrado.</p>";
            break;
        case 'parametros_invalidos':
            echo "<p style='color:red;'>Parámetros incompletos o inválidos.</p>";
            break;
        case 'error_agregar_miembro':
            echo "<p style='color:red;'>Error al agregar el miembro al grupo.</p>";
            break;
    }
}

// Capturamos el contenido generado en el buffer
$contenidoPrincipal = ob_get_clean();

// Título de la página
$tituloPagina = "Grupos";

// Incluimos la plantilla principal
require __DIR__ . '/includes/vistas/plantilla/plantilla.php';