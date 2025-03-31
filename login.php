<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/FormularioLogin.php';

$form = new FormularioLogin();
ob_start();
?>
<div class="login-container">
    <h1>Acceso al sistema</h1>
    <?php echo $form->gestiona(); ?>
</div>
<?php
$htmlFormLogin = ob_get_clean();

$tituloPagina = 'Login';
$contenidoPrincipal = $htmlFormLogin;

require __DIR__ . '/includes/vistas/plantilla/plantilla.php';
