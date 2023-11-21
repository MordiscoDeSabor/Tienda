<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['cliente_id'])) {
    echo "Debes iniciar sesión para acceder al historial de compras.";
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mordisco de sabor</title>
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="js/script.js"></script>
    <style>
        /* Estilos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
        <img src="images/baner.jpg" alt="Logo" class="logo" style="width: 50px; height: 50px;"> <h1>Mordisco de sabor  -* COMPRAS *-</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li> 
                <li><a href="productos.php">Productos</a></li>                
                <li><a href="carrito.php"><i class="fas fa-shopping-cart"></i> </a></li>
               
                <li><a href='perfil.php'><i class='fas fa-user-cog'></i> Perfil</a></li>
            </ul>
        </nav>
    </header>
    <div class="contenido">
        <?php
        // Conexión a la base de datos
        $host = 'localhost';
        $dbname = 'base';
        $username = 'root';
        $password = '';

        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Obtener el cliente_id del usuario actual
            $clienteId = $_SESSION['cliente_id'];

            // Consulta para obtener el historial de compras del cliente
            $query = "SELECT * FROM compras WHERE cliente_id = :cliente_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':cliente_id', $clienteId);
            $stmt->execute();

            // Verificar si se encontraron registros
            if ($stmt->rowCount() > 0) {
                // Mostrar los resultados en una tabla
                echo "<table>";
                echo "<tr>";
                echo "<th>Fecha de Compra</th>";
                echo "<th>Producto</th>";
                echo "<th>Cantidad</th>";
                echo "<th>Total</th>";
                echo "</tr>";

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>{$row['fecha']}</td>";
                    echo "<td>{$row['nombre']}</td>";
                    echo "<continuación...</td>";
                    echo "<td>{$row['cantidad']}</td>";
                    echo "<td>{$row['total']}</td>";
                    echo "</tr>";
                            }
                    echo "</table>";
                } else {
                    echo "No se encontraron registros de compras.";
                }
            } catch (PDOException $e) {
                echo "Error de conexión a la base de datos: " . $e->getMessage();
                exit();
            }
            ?>
        </div>

        <footer>
            <p>Informacion detallada de las galletas</p>
            <p>Este es nuestro  <a href="contacto.html">Contacto</a> Todos los derechos reservados &copy; 2023.</p>
        </footer>
        </body>
        </html>