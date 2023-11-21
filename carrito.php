
<!DOCTYPE html>
<html>
<head>
    <title>Mordisco de sabor</title>
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <script src="js/script.js"></script>
    

</head>
<body>
    <header>
        <img src="images/baner.jpg" alt="Logo" class="logo" style="width: 50px; height: 50px;"><h1>Mordisco de sabor</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li> 
                <li><a href="productos.php">Productos</a></li>                
                <li><a href="historial_compras.php">Compras</a></li>
                 <li><a href='perfil.php'><i class='fas fa-user-cog'></i> Perfil</a></li>                
            </ul>
        </nav>
    </header>
        <section id="destacados">
            
<?php
session_start();
echo "<link rel='stylesheet' type='text/css' href='css/estilo_carrito.css'>";

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['cliente_id'])) {
    echo "Debes iniciar sesión para acceder al carrito.";
    header("Location: login.php");
    exit();
}



// Verificar si se enviaron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del producto del formulario
    $productoId = $_POST['producto_id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen'];
    $cantidad = $_POST['cantidad'];

   // Verificar si el producto ya existe en el carrito
$productoExistente = false;
foreach ($_SESSION['carrito'] as &$item) {
    if ($item['producto_id'] == $productoId) {
        $item['cantidad'] += $cantidad;
        $productoExistente = true;
        break;
    }
}

// Si el producto no existe, agregarlo al carrito
if (!$productoExistente) {
    // Crear el arreglo de producto
    $producto = array(
        'producto_id' => $productoId,
        'nombre' => $nombre,
        'precio' => $precio * $cantidad, // Actualizar el precio con la cantidad
        'imagen' => $imagen,
        'cantidad' => $cantidad
    );

    $_SESSION['carrito'][] = $producto;
}

    // Insertar el producto en la tabla carrito de la base de datos
    $host = 'localhost';
    $dbname = 'base';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar la consulta de inserción
        $query = "INSERT INTO carrito (cliente_id, producto_id, imagen, descripcion, unidades, cantidad, precio, total) VALUES (:cliente_id, :producto_id, :imagen, :descripcion, :unidades, :cantidad, :precio, :total)";
        $stmt = $conn->prepare($query);

        // Obtener el cliente_id del usuario actual
        $clienteId = $_SESSION['cliente_id'];

        // Calcular el total
        $total = $precio * $cantidad;

        // Asignar los valores a los parámetros de la consulta
        $stmt->bindParam(':cliente_id', $clienteId);
        $stmt->bindParam(':producto_id', $productoId);
        $stmt->bindParam(':imagen', $imagen);
        $stmt->bindParam(':descripcion', $nombre);
        $stmt->bindParam(':unidades', $cantidad);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':total', $total);

        // Ejecutar la consulta de inserción
                $stmt->execute();

        // Mostrar la alerta
        echo "<script>alert('El producto se ha agregado al carrito.');</script>";
    } catch (PDOException $e) {
        echo "Error de conexión a la base de datos: " . $e->getMessage();
        exit();
    }

    // Redirigir de vuelta a la página de productos
    header("Location: productos.php");
    exit();
}

// Obtener el cliente_id del usuario actual
$clienteId = $_SESSION['cliente_id'];

// Obtener los productos del carrito para el cliente actual
$host = 'localhost';
$dbname = 'base';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener los productos del carrito para el cliente actual
    $query = "SELECT * FROM carrito WHERE cliente_id = :cliente_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':cliente_id', $clienteId);
    $stmt->execute();

    // Crear un array para almacenar los productos del carrito
    $productosCarrito = array();

    // Recorrer los productos del carrito
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $producto = array(
            'producto_id' => $row['producto_id'],
            'nombre' => $row['descripcion'],
            'cantidad' => $row['cantidad'],
            'precio' => $row['precio'],
            'imagen' => $row['imagen']
        );
        $productosCarrito[] = $producto;
    }



 // Mostrar los productos del carrito en una tabla
    echo "<table>";
    echo "<tr>";
    echo "<th> </th>";
    echo "<th>Producto</th>";
    echo "<th>Cantidad</th>";
    echo "<th>Precio</th>";
    
    echo "</tr>";

    // Mostrar los productos del carrito
    foreach ($productosCarrito as $producto) {
        echo "<tr>";
        echo "<td>";
        echo "<img src='images/{$producto['imagen']}' alt='Imagen del producto {$producto['nombre']}' width='50'>";
        echo "</td>";
        echo "<td>{$producto['nombre']}";
        echo "</td>";
        echo "<td>{$producto['cantidad']}</td>";
        echo "<td>" . ($producto['precio'] * $producto['cantidad']) . "</td>";
        echo "</tr>";
    }

    // Calcular la cantidad total a pagar
    $cantidadTotal = 0;
    foreach ($productosCarrito as $producto) {
        $cantidadTotal += ($producto['precio'] * $producto['cantidad']);
    }

    // Mostrar la fila de cantidad total
    echo "<tr class='total-row'>";
    echo "<td colspan='3'>Cantidad a pagar:</td>";
    echo "<td>{$cantidadTotal}</td>";
    echo "</tr>";
    echo "</table>";

} catch (PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit();
}

// Botones para pagar
echo "<button class='button-pagar' onclick='pagar()'>Pagar</button>";

// JavaScript para la función de pagar
echo "<script>
function pagar() {
    // Realizar las acciones necesarias para el pago
    window.location.href = 'proceso_compra.php';
}
</script>";

echo "<style>
.button-pagar {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 10px 0;
  cursor: pointer;
  border-radius: 5px;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
}

.button-pagar:hover {
  background-color: #0056b3;
}
</style>";

?>

        </section>
    </div>

</body>
</html>
<style>
.button-pagar {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 10px 0;
  cursor: pointer;
  border-radius: 5px;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
}

.button-pagar:hover {
  background-color: #0056b3;
}
</style>
