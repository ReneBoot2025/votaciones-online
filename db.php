<?php
$conexion = new mysqli("localhost", "root", "1111", "votacion");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
