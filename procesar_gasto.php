<?php
session_start();
require_once 'includes/config.php';  // Archivo para conectar a la BD

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $tipo = $_POST['tipo'] ?? 'Gasto'; // 'Ingreso' o 'Gasto'
    $monto = floatval($_POST['monto']);
    $fecha = $_POST['fecha'];
    $comentario = $_POST['comentario'] ?? '';

    // Revisamos la categoría elegida
    $categoria_id = $_POST['categoria_id'] ?? '';

    if ($categoria_id === 'otra') {
        // El usuario eligió "Otra categoría"
        $categoriaNueva = trim($_POST['categoria_nueva'] ?? '');
        if ($categoriaNueva !== '') {
            // Insertamos la nueva categoría en la tabla `categorias`
            $sqlInsertCat = "INSERT INTO categorias (nombre) VALUES (?)";
            $stmtCat = $conn->prepare($sqlInsertCat);
            $stmtCat->bind_param("s", $categoriaNueva);
            if ($stmtCat->execute()) {
                // Obtenemos el id de la nueva categoría
                $categoria_id = $conn->insert_id;
            } else {
                echo "Error al crear la nueva categoría.";
                exit;
            }
            $stmtCat->close();
        } else {
            // Si no se escribió nada, podrías manejar un error o usar un valor por defecto
            echo "No se ha especificado una nueva categoría.";
            exit;
        }
    } else {
        // El usuario eligió una categoría existente (un id)
        $categoria_id = intval($categoria_id);
    }
    
    // Insertamos el gasto en la tabla `gastos`
    $sql = "INSERT INTO gastos (usuario_id, tipo, categoria_id, monto, fecha, comentario)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isidss", $user_id, $tipo, $categoria_id, $monto, $fecha, $comentario);

    if ($stmt->execute()) {
        echo "Gasto registrado correctamente.";
        header("Location: gastos.php");  // Redirigir de vuelta al formulario
        exit;
    } else {
        echo "Error al registrar el gasto.";
    }

    $stmt->close();
    $conn->close();
}
?>
