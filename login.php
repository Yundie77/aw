<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'includes/mysql/Datos.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare statement
    $stmt = $conn->prepare("SELECT id, nombre, password, tipo_usuario FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nombre, $hashed_password, $tipo_usuario);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Correct password => set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_type'] = $tipo_usuario;
            
            // Redirect based on user type
            if ($tipo_usuario == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.html");
            }
            exit();
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - CampusCash</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    
    <form method="post" action="login.php">
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Ingresar</button>
    </form>
</body>
</html>
