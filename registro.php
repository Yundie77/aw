<?php
session_start();
require_once __DIR__ . '/config.php';
use es\ucm\fdi\aw\FormularioRegistro;

$tituloPagina = "Registro - CampusCash";
$formulario = new FormularioRegistro();
$htmlFormulario = $formulario->gestiona();

// Contenido principal que se pasará a la plantilla
$contenidoPrincipal = <<<EOS
<div class="container">
    <h1>Registro</h1>
    $htmlFormulario
</div>
EOS;

// Incluye la plantilla
require __DIR__ . '/includes/vistas/plantilla/plantilla.php';
