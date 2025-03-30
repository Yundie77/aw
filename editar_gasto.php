<?php
session_start();
require_once 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID inválido");
}

// Consulta el registro
$sql = "SELECT * FROM gastos WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$user_id = $_SESSION['usuario_id'];
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Registro no encontrado");
}
$gasto = $result->fetch_assoc();
$stmt->close();

// El formulario con los datos del gasto para editar
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Gasto</title>
</head>
<body>
<h2>Editar Gasto</h2>
<form action="actualizar_gasto.php" method="POST">
  <input type="hidden" name="id" value="<?php echo $gasto['id']; ?>">
  <label>Tipo:</label>
  <select name="tipo" required>
    <option value="Ingreso" <?php if($gasto['tipo'] === 'Ingreso') echo "selected"; ?>>Ingreso</option>
    <option value="Gasto" <?php if($gasto['tipo'] === 'Gasto') echo "selected"; ?>>Gasto</option>
  </select><br>
  <label>Monto (€):</label>
  <input type="number" name="monto" step="0.01" required value="<?php echo $gasto['monto']; ?>"><br>
  <label>Fecha:</label>
  <input type="date" name="fecha" required value="<?php echo $gasto['fecha']; ?>"><br>
  <label>Comentario:</label>
  <textarea name="comentario"><?php echo htmlspecialchars($gasto['comentario']); ?></textarea><br>
  <!-- Agregar campo para categoría si es necesario -->
  <button type="submit">Actualizar</button>
</form>
</body>
</html>
