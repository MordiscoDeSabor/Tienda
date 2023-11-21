<?php
session_start();

// Verificar si la sesión está activa
if (!isset($_SESSION['carrito'])) {
    // Redirigir a la página de productos si no hay productos en el carrito
    header("Location: productos.php");
    exit();
}

// Obtener los productos del carrito para el cliente actual
$productosCarrito = $_SESSION['carrito'];

// Insertar la compra en la tabla "compras" de la base de datos
$host = 'localhost';
$dbname = 'base';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el cliente_id (asumiendo que lo tienes almacenado en alguna variable)
    $clienteId = $_SESSION['cliente_id']; // Reemplaza 'cliente_id' con la forma correcta de obtener el cliente_id

    // Obtener la fecha actual
    $fecha = date("Y-m-d");

   
     // Borrar los productos del carrito de la base de datos
    $query = "DELETE FROM carrito WHERE cliente_id = :cliente_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':cliente_id', $clienteId);
    $stmt->execute();

    // Limpiar el carrito después de realizar la compra
    $_SESSION['carrito'] = array();

    // Redirigir al usuario a la página de inicio con una alerta de pago exitoso
    echo "<script>alert('Pago exitoso. Gracias por su compra.');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
} catch (PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit();
}
?>
