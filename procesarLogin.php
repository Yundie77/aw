<?php
session_start();
require_once __DIR__ . '/includes/config.php';

$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar datos
    $username = trim($_POST['username']); 
    $password = $_POST['password'];
    
    // Preparar la consulta para evitar inyección SQL
    // Se busca por correo electrónico o por nombre (usuario)
    $stmt = $conn->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = ? OR nombre = ?");
    if (!$stmt) {
        $_SESSION['error'] = "Error en la consulta";
        header("Location: login.php");
        exit();
    }
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $stmt->store_result();

    // Verificar si se encontró el usuario
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nombre, $stored_password, $rol);
        $stmt->fetch();
        // Cambiar comparación directa por password_verify
        if (password_verify($password, $stored_password)) {
            // Autenticación exitosa: almacenar datos en sesión
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_role'] = $rol;
            
            // Redirigir según el rol
            if ($rol == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
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
