<?php
$host = 'localhost';
$user = 'root';
$pass = 'admin';
$db = 'dulzon';

$conexion = new mysqli($host, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>


