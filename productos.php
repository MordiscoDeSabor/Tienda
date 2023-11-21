<!DOCTYPE html>
<html>
<head>
    <title>Productos</title>
    <link rel="stylesheet" type="text/css" href="css/estilo_p.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script>
        function agregarAlCarrito(productoId, precio, nombre, imagen) {
            var form = document.createElement("form");
            form.method = "post";
            form.action = "carrito.php";

            var productoIdInput = document.createElement("input");
            productoIdInput.type = "hidden";
            productoIdInput.name = "producto_id";
            productoIdInput.value = productoId;
            form.appendChild(productoIdInput);

            var nombreInput = document.createElement("input");
            nombreInput.type = "hidden";
            nombreInput.name = "nombre";
            nombreInput.value = nombre;
            form.appendChild(nombreInput);

            var precioInput = document.createElement("input");
            precioInput.type = "hidden";
            precioInput.name = "precio";
            precioInput.value = precio;
            form.appendChild(precioInput);

            var imagenInput = document.createElement("input");
            imagenInput.type = "hidden";
            imagenInput.name = "imagen";
            imagenInput.value = imagen;
            form.appendChild(imagenInput);

            var cantidadInput = document.createElement("input");
            cantidadInput.type = "hidden";
            cantidadInput.name = "cantidad";
            cantidadInput.value = 1;
            form.appendChild(cantidadInput);

            document.body.appendChild(form);
            form.submit();

            // Mostrar la alerta
            alert("El producto se ha agregado al carrito.");
        }
    </script>
</head>
<body>
    <h1>Productos</h1>
     <header>
        <h1>Mordisco de sabor</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li> 
                <li><a href="carrito.php"><i class="fas fa-shopping-cart"></i> </a></li>
                <li><a href="historial_compras.php">Compras</a></li>  
                 <li><a href='perfil.php'><i class='fas fa-user-cog'></i> Perfil</a></li>
                 
            </ul>
        </nav>
    </header>
    <?php
    session_start();

    // Configuración de la base de datos
    $host = 'localhost';
    $dbname = 'base';
    $username = 'root';
    $password = '';

    // Establecer conexión a la base de datos
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Error de conexión a la base de datos: " . $e->getMessage();
        exit();
    }

    // Consulta para obtener los productos desde la base de datos
    $query = "SELECT id, nombre,descripcion, precio, imagen FROM productos WHERE activo = 1";
    $stmt = $conn->query($query);

    // Mostrar los productos
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div class='producto'>";
        echo "<h3>{$row['nombre']}</h3>";
        echo "<img src='images/{$row['imagen']}' alt='Imagen del producto'>";
        echo "<p>Precio: {$row['precio']}</p>";
        echo "<p>Descripcion: {$row['descripcion']}</p>";

        // Agregar el formulario para agregar al carrito
        echo "<button type='button' onclick=\"agregarAlCarrito({$row['id']}, {$row['precio']}, '{$row['nombre']}', '{$row['imagen']}')\">Agregar al carrito</button>";

        echo "</div>";
    }
    ?>
    

</body>
</html>
