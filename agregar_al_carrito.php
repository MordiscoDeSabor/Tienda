<?php
session_start();

// Verificar si se envió la solicitud por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $productoId = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    // Verificar si el carrito ya existe en la sesión
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }

    // Verificar si el producto ya está en el carrito
    $carrito = $_SESSION['carrito'];
    if (array_key_exists($productoId, $carrito)) {
        // Si el producto ya está en el carrito, aumentar la cantidad
        $carrito[$productoId]['cantidad'] += $cantidad;
    } else {
        // Si el producto no está en el carrito, agregarlo
        $carrito[$productoId] = array(
            'producto_id' => $productoId,
            'cantidad' => $cantidad
        );
    }

    // Actualizar el carrito en la sesión
    $_SESSION['carrito'] = $carrito;

    // Enviar una respuesta al cliente
    echo "El producto se agregó al carrito exitosamente.";
}
?>
