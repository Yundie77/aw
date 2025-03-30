<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <title><?= $tituloPagina ?></title>
    <link rel="stylesheet" type="text/css" href="<?= RUTA_CSS ?>/style.css" />
</head>
<body>
<div id="contenedor">
<?php
require(RAIZ_APP.'/includes/vistas/comun/header.php');
require(RAIZ_APP.'/includes/vistas/comun/nav.php');
?>
	<main>
		<article>
			<?= $contenidoPrincipal ?>
		</article>
	</main>
<?php
require(RAIZ_APP.'/includes/vistas/comun/footer.php');
?>
</div>
</body>
</html>
