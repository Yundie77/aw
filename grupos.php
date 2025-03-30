<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para acceder a esta funcionalidad.";
    header("Location: login.php");
    exit();
}
require 'includes/vistas/comun/header.php';
require 'includes/vistas/comun/nav.php';
require_once 'config.php';

// Actualizamos la query para calcular el número real de participantes para cada grupo
$result = $conn->query("SELECT g.*, (SELECT COUNT(*) FROM grupo_usuarios WHERE grupo_id = g.id) AS participantes FROM grupos g");
if (!$result) {
    die("Query error: " . $conn->error);
}
$grupos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Grupos</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="grupo-list">
        <?php if(count($grupos) > 0): ?>
            <?php foreach($grupos as $grupo): ?>
            <div class="grupo-item">
                <h2><?php echo htmlspecialchars($grupo['nombre']); ?></h2>
                <p>(<?php echo htmlspecialchars($grupo['participantes']); ?> participantes)</p>
                <p>Objetivo: <?php echo htmlspecialchars($grupo['objetivo']); ?>$</p>
                <a href="grupo_detalles.php?id=<?php echo htmlspecialchars($grupo['id']); ?>" class="ver-detalles">Ver detalles</a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-grupos">
                <p>No hay grupos disponibles</p>
            </div>
        <?php endif; ?>
    </div>
    
    <?php require 'includes/vistas/comun/footer.php'; ?>
</body>
</html>