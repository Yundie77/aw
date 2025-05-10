<?php
session_start();
require_once 'includes/config.php';

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\FormularioEditarGastoGrupal;

$app = Aplicacion::getInstance();
$grupo_id = isset($_GET['grupo_id']) ? (int)$_GET['grupo_id'] : 0;

$form = new FormularioEditarGastoGrupal($grupo_id);
$form->gestiona();

header("Location: historial_gasto_grupal.php?id=$grupo_id");
exit;
