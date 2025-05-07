<?php
session_start();
require_once __DIR__ . '/includes/config.php';
use es\ucm\fdi\aw\FormularioRegistro;

$tituloPagina = "Registro - CampusCash";
$formulario = new FormularioRegistro();
$htmlFormulario = $formulario->gestiona();

// Contenido principal que se pasará a la plantilla
$contenidoPrincipal = <<<EOS

$htmlFormulario

EOS;


// Incluye la plantilla
require __DIR__ . '/includes/vistas/plantilla/plantilla.php';
