<?php
// Realizar la conexión a la base de datos (cambia los valores según tu configuración)
$host = 'localhost';
$dbname = 'base';
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Datos del nuevo administrador
    $nombre = 'Lian';
    $email = 'admin3@gmail.com';
    $contrasena = 'admin';

    // Consulta SQL para insertar el nuevo administrador en la tabla
    $stmt = $pdo->prepare('INSERT INTO administradores (nombre, email, contrasena) VALUES (:nombre, :email, :contrasena)');
    $stmt->execute(array(':nombre' => $nombre, ':email' => $email, ':contrasena' => $contrasena));

    echo 'Administrador creado exitosamente.';
} catch (PDOException $e) {
    echo 'Error de conexión a la base de datos: ' . $e->getMessage();
}
?>
