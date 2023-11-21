<?php
$servername = "localhost"; // Nombre del servidor (puede variar dependiendo de tu configuración)
$username = "root"; // Nombre de usuario de MySQL
$password = ""; // Contraseña de MySQL
$dbname = "base"; // Nombre de la base de datos

// Establecer la conexión con la base de datos
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if (!$conn) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}
?>
