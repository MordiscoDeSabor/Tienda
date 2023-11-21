<?php
session_start();

// Verificar si el formulario de inicio de sesión ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener las credenciales ingresadas en el formulario
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    // Realizar la conexión a la base de datos (cambia los valores según tu configuración)
    $host = 'localhost';
    $dbname = 'base';
    $username_db = 'root';
    $password_db = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consultar la tabla de clientes  para verificar las credenciales
        $stmt = $pdo->prepare('SELECT id, nombre  FROM clientes  WHERE email = :email AND contrasena = :contrasena');
        $stmt->execute(array(':email' => $email, ':contrasena' => $contrasena));

        if ($stmt->rowCount() === 1) {
            // Las credenciales son válidas, establecer las variables de sesión
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['cliente_id'] = $row['id'];
            $_SESSION['cliente_nombre'] = $row['nombre'];

            header('Location: index.php'); // Redirigir a la página principal después del inicio de sesión
            exit;
        } else {
            $error = 'Credenciales inválidas'; // Mensaje de error para mostrar en el formulario
        }
    } catch (PDOException $e) {
        echo 'Error de conexión a la base de datos: ' . $e->getMessage();
    }
}

// Cerrar sesión
if(isset($_SESSION['cliente_email'])){
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Iniciar sesión</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #4caf50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Iniciar sesión</h2>
    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required><br>
        <button type="submit">Iniciar sesión</button>
    </form>
    
    <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
    <p><a href="index.php?logout=true">Cerrar sesión</a></p>
</body>
</html>
