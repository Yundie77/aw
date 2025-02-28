<?php
session_start();
require '../includes/header.php';
require '../includes/nav.php';
require_once '../config.php';

$sql = "SELECT categoria, monto, fecha, comentario FROM gastos WHERE usuario_id = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Gastos</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<h2 style="text-align:center;">Historial de Gastos</h2>

<table class="tabla-gastos">
  <tr>
    <th>Categoría</th>
    <th>Monto (€)</th>
    <th>Fecha</th>
    <th>Comentario</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $row["categoria"]; ?></td>
        <td><?php echo $row["monto"]; ?></td>
        <td><?php echo $row["fecha"]; ?></td>
        <td><?php echo $row["comentario"]; ?></td>
      </tr>
  <?php } ?>
</table>

</body>
</html>
