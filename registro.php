<?php
session_start();

// Verificar si el formulario de registro ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos ingresados en el formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $direccion = $_POST['direccion'];
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    // Realizar la conexión a la base de datos 
    $host = 'localhost';
    $dbname = 'base';
    $username_db = 'root';
    $password_db = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar si ya existe un usuario con el mismo correo electrónico
        $stmt = $pdo->prepare('SELECT * FROM clientes WHERE email = :email');
        $stmt->execute(array(':email' => $email));

        if ($stmt->rowCount() === 0) {
            // No existe un usuario con el mismo correo electrónico, insertar el nuevo usuario en la base de datos
            $stmt = $pdo->prepare('INSERT INTO clientes (nombre, apellido, direccion, email, contrasena) VALUES (:nombre, :apellido, :direccion, :email, :contrasena)');
            $stmt->execute(array(':nombre' => $nombre, ':apellido' => $apellido, ':direccion' => $direccion, ':email' => $email, ':contrasena' => $contrasena));

            $_SESSION['cliente_id'] = $pdo->lastInsertId();
            $_SESSION['cliente_nombre'] = $nombre;

            header('Location: index.php'); // Redirigir a la página principal después del registro
            exit;
        } else {
            $error = 'Ya existe un usuario con el mismo correo electrónico'; // Mensaje de error para mostrar en el formulario
        }
    } catch (PDOException $e) {
        echo 'Error de conexión a la base de datos: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de usuario</title>
    <link rel="stylesheet" type="text/css" href="css/es_registro.css">
</head>
<body>
    <h2>Registro de usuario</h2>
    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form method="POST" action="">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required><br>
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required><br>
        <button type="submit">Registrarse</button>
            </form>

<p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</body>
</html>

