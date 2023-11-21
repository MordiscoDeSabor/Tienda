<?php
session_start();

// Verificar si la sesión está activa y el cliente_id está definido
if (!isset($_SESSION['carrito']) || !isset($_SESSION['cliente_id'])) {
    // Redirigir a la página de productos o al inicio de sesión según sea necesario
    header("Location: productos.php"); // O header("Location: login.php");
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

    // Obtener el cliente_id (asumiendo que lo tienes almacenado en alguna variable o función)
    $clienteId = $_SESSION['cliente_id']; // Reemplaza $_SESSION['cliente_id'] con la forma correcta de obtener el cliente_id

    // Obtener la fecha actual
    $fecha = date("Y-m-d");

    // Recorrer los productos del carrito para insertarlos en la tabla "compras"
    foreach ($productosCarrito as $producto) {
        // Verificar si la clave 'descripcion' está definida en el arreglo $producto y filtrar los datos
        $descripcion = isset($producto['descripcion']) ? filter_var($producto['descripcion'], FILTER_SANITIZE_STRING) : '';

        $productoId = filter_var($producto['producto_id'], FILTER_SANITIZE_NUMBER_INT);
        $imagen = filter_var($producto['imagen'], FILTER_SANITIZE_STRING);
        $nombre = filter_var($producto['nombre'], FILTER_SANITIZE_STRING);
        $cantidad = filter_var($producto['cantidad'], FILTER_SANITIZE_NUMBER_INT);
        $total = filter_var($producto['precio'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        // Preparar la consulta de inserción
        $query = "INSERT INTO compras (cliente_id, producto_id, imagen, nombre, descripcion, cantidad, total, fecha) 
                  VALUES (:cliente_id, :producto_id, :imagen, :nombre, :descripcion, :cantidad, :total, :fecha)";
        $stmt = $conn->prepare($query);

        // Asignar los valores a los parámetros de la consulta
        $stmt->bindParam(':cliente_id', $clienteId, PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $productoId, PDO::PARAM_INT);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':total', $total, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);

        // Ejecutar la consulta de inserción
        $stmt->execute();
    }

    // Redirigir al usuario a la página de éxito de pago
    header("Location: pago.php");
    exit();
} catch (PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit();
}
?>
