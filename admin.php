<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require_once __DIR__ . '/includes/vistas/comun/nav.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="admin-container">
    <h2>Administrador</h2>
    <div class="search-box">
      <h3>Buscar usuarios</h3>
      <input type="text" placeholder="Buscar por nombre, email..." />
    </div>

    <table class="user-table">
      <thead>
        <tr>
          <th>#ID</th>
          <th>Nombre</th>
          <th>Género</th>
          <th>Email</th>
          <th>Tipo</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>#001</td>
          <td>Pedro</td>
          <td>Hombre</td>
          <td>pedgen@ucm.es</td>
          <td>Admin</td>
          <td>Activo</td>
        </tr>
        <tr>
          <td>#002</td>
          <td>Luis</td>
          <td>Hombre</td>
          <td>lui@ucm.es</td>
          <td>Normal</td>
          <td>Inactivo</td>
        </tr>
      </tbody>
    </table>
  </div>
</body>
</html>
