<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/clases/FormularioLogin.php';

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

require_once RAIZ_APP . '/vistas/plantilla/plantilla.php';
