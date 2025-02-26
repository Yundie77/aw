<?php
$servername = "localhost";
$username = "normal";
$password = "";
$dbname = "aw";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
