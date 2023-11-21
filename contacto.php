<!DOCTYPE html>
<html>
<head>
    <title>Página de Contacto</title>
     <link rel="stylesheet" type="text/css" href="css/estilos.css">
    
    <style>
        /* Estilos CSS para la página de contacto */
        .contacto {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f2f2f2;
        }
        
        .contacto label, .contacto input, .contacto textarea {
            display: block;
            margin-bottom: 10px;
        }
        
        .contacto button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

/* Pie de página */
footer {
    background-color: #f2f2f2;
    padding: 20px;
    text-align: center;
    color: #666666;
}

footer p {
    font-size: 14px;
}

    </style>
</head>
<body>
    <header>
         <img src="images/baner.jpg" alt="Logo" class="logo" style="width: 50px; height: 50px;"><h1>Mordisco de sabor</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li> 
               
                 
            </ul>
        </nav>
    </header>
    <div class="contacto">
        <h2>Formulario de Contacto</h2>
        <?php
        // Establecer la conexión con la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "base";

        $conn = new mysqli($servername, $username, $password, $database);
        if ($conn->connect_error) {
            die("Error al conectar con la base de datos: " . $conn->connect_error);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener los datos del formulario
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $mensaje = $_POST['mensaje'];
            
            // Insertar los datos en la base de datos
            $sql = "INSERT INTO tabla_contactos (nombre, email, mensaje) VALUES ('$nombre', '$email', '$mensaje')";

            if ($conn->query($sql) === TRUE) {
                // Mostrar la alerta de mensaje enviado
                echo '<script>alert("Mensaje enviado"); window.location.href = "index.php";</script>';
            } else {
                echo "Error al guardar el mensaje en la base de datos: " . $conn->error;
            }
        }

        // Cerrar la conexión con la base de datos
        $conn->close();
        ?>
        <form method="POST" action="">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" rows="4" required></textarea>
            
            <button type="submit">Enviar</button>
        </form>
    </div>
    <footer>
    <p>Nombre Alvaro Jesus Gonzalez Ramirez</p>
  <p>correo de contacto alokgonzalez5@gmail.com</p>
   <p>Numero al cual dirigirte 7831385958</p>
    <p>Direccion  calle: Azteca #2, colonia: Unidad Antorchista </p>
</footer>
</body>
</html>
