<?php require_once '../includes/header.php'; ?>
<?php require_once '../includes/nav.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Gasto</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>Registro de Gasto</h2>
    <form action="../controllers/procesar_gasto.php" method="POST">
        <label for="categoria">Categoría:</label>
        <select name="categoria" required>
            <option value="Ropa">Ropa</option>
            <option value="Comida">Comida</option>
            <option value="Ocio">Ocio</option>
            <option value="Salud">Salud</option>
            <option value="Transporte">Transporte</option>
        </select>
        
        <label for="monto">Monto (€):</label>
        <input type="number" name="monto" step="0.01" required>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" required>

        <label for="comentario">Comentario:</label>
        <textarea name="comentario"></textarea>

        <button type="submit">Registrar Gasto</button>
    </form>
</body>
</html>
