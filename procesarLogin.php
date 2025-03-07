<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/vistas/comun/header.php';
require_once __DIR__ . '/includes/vistas/comun/nav.php';
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar datos
    $email = trim($_POST['email']); 
    $password = $_POST['password'];
    
    // Preparar la consulta para evitar inyección SQL
    $stmt = $conn->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = ?");
    if (!$stmt) {
        $_SESSION['error'] = "Error en la consulta";
        header("Location: login.php");
        exit();
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Verificar si se encontró el usuario
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nombre, $hashed_password, $rol);
        $stmt->fetch();
        // Verificar la contraseña
        if (password_verify($password, $hashed_password)) {
            // Autenticación exitosa: almacenar datos en sesión
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_role'] = $rol;
            
            // Redirigir según el rol
            if ($rol == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Usuario no encontrado";
        header("Location: login.php");
        exit();
    }
} else {
    // Si no se recibió una petición POST, redirige al formulario de login
    header("Location: login.php");
    exit();
}
?>
