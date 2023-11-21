<?php
session_start();

// Verificar si la sesión está activa y si el usuario está autenticado
if (!isset($_SESSION['cliente_id']) || !isset($_SESSION['cliente_nombre'])) {
    // Redirigir a la página de inicio de sesión si no hay sesión activa
    header("Location: login.php");
    exit();
}

// Obtener el cliente_id y el nombre del cliente desde la sesión
$clienteId = $_SESSION['cliente_id'];
$clienteNombre = $_SESSION['cliente_nombre'];

// Obtener los datos actuales del cliente de la base de datos
$host = 'localhost';
$dbname = 'base';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener los datos actuales del cliente de la tabla "clientes"
    $query = "SELECT nombre, apellido, direccion, email FROM clientes WHERE id = :cliente_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':cliente_id', $clienteId);
    $stmt->execute();

    // Obtener los datos del cliente
    $datosCliente = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el formulario ha sido enviado para actualizar la información
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los nuevos datos del formulario
        $nuevoNombre = $_POST['nombre'];
        $nuevoApellido = $_POST['apellido'];
        $nuevaDireccion = $_POST['direccion'];
        $nuevoEmail = $_POST['email'];

        // Actualizar la información del cliente en la base de datos
        $query = "UPDATE clientes SET nombre = :nombre, apellido = :apellido, direccion = :direccion, email = :email WHERE id = :cliente_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nombre', $nuevoNombre);
        $stmt->bindParam(':apellido', $nuevoApellido);
        $stmt->bindParam(':direccion', $nuevaDireccion);
        $stmt->bindParam(':email', $nuevoEmail);
        $stmt->bindParam(':cliente_id', $clienteId);
        $stmt->execute();

        // Actualizar los datos del cliente en la sesión
        $_SESSION['cliente_nombre'] = $nuevoNombre;

     
        exit();
    }
} catch (PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Actualizar Información</title>
    <link rel="stylesheet" href="css/perfil.css">
</head>
<body>
    <h2>Actualizar Información</h2>
    <p>Bienvenido, <?php echo $clienteNombre; ?>. Actualiza tus datos a continuación:</p>
    <form method="post" action="">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $datosCliente['nombre']; ?>" required><br>

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" value="<?php echo $datosCliente['apellido']; ?>" required><br>

                <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" value="<?php echo $datosCliente['direccion']; ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $datosCliente['email']; ?>" required><br>

        <a href="metodo_pago.html" style="display: inline-block; padding: 10px 20px; background-color: #f0f0f0; border: 1px solid #ccc; text-decoration: none; color: #000;">Actualizar Información</a>


   
    </form>
</body>
</html>


   
?>

