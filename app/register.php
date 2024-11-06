<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #444;
            padding: 20px;
            background-color: #fff;
            margin: 0;
            border-bottom: 2px solid #ddd;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .mensaje {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 2px solid #ddd;
            margin-top: 20px;
        }

        footer p {
            margin: 0;
            color: #777;
        }

        nav {
            text-align: center;
            background-color: #f8f9fa;
            padding: 15px 0;
            border-top: 2px solid #ddd;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            padding: 10px 20px;
            background-color: #0069d9;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #0056b3;
        }
    </style>
    <script src="comprobaciones.js"></script>
</head>
<body>
    <h1>Registro</h1>

    <div class="container">
        <form id="register_form" action="register.php" method="POST" onsubmit="return validarFormulario();">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" placeholder="Ejemplo: Juan123" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required>

            <label for="mail">Correo Electrónico:</label>
            <input type="email" id="mail" name="mail" placeholder="Ejemplo: nombre@servidor.com" required>

            <label for="nombre_apellidos">Nombre y Apellidos:</label>
            <input type="text" id="nombre_apellidos" name="nombre_apellidos" placeholder="Ejemplo: Juan Pérez Gómez" required>

            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" placeholder="Ejemplo: 12345678Z" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" placeholder="Ejemplo: 600123456" required>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>

            <button id="register_submit" name="submit" type="submit">Registrarse</button>
        </form>

        <!-- Contenedor para mostrar mensajes -->
        <div id="mensaje" class="mensaje">
            <?php
            // Inicializar la variable de mensaje
            $mensaje = '';

            // Conexión a la base de datos
            $servername = "db";
            $username_db = "admin";  // Cambiar por tu usuario de MySQL
            $password_db = "test";      // Cambiar por tu contraseña de MySQL
            $dbname = "database";  // Nombre de tu base de datos

            // Crear conexión
            $conn = new mysqli($servername, $username_db, $password_db, $dbname);

            // Verificar conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Verificar si el botón ha sido presionado
            if (isset($_POST['submit'])) {
    // Obtener datos del formulario
    	    $username = $_POST['username'];
            $nombre_apellidos = $_POST['nombre_apellidos'];
    	    $email = $_POST['mail'];
    	    $password = $_POST['password'];
    	    $dni = $_POST['dni'];
    	    $telefono = $_POST['telefono'];
 	    $fecha_nacimiento = $_POST['fecha_nacimiento'];

  	    $sql = "INSERT INTO usuarios (nombre_apellidos, dni, telefono, fecha_nacimiento, email, username, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

     	    $stmt = $conn->prepare($sql);

	    // Verificar si se preparó correctamente
	    if ($stmt === false) {
		$mensaje = "<span style='color: red;'>Error en la preparación de la consulta.</span>";
	    } else {
		// Enlazar los parámetros (s indica que todos son strings)
		$stmt->bind_param("sssssss", $nombre_apellidos, $dni, $telefono, $fecha_nacimiento, $email, $username, $password);

		// Ejecutar la consulta
		if ($stmt->execute()) {
		    $mensaje = "<span style='color: green;'>Registro exitoso.</span>"; // Mensaje de éxito
		} else {
		    // Manejo de errores
		    if ($stmt->errno === 1062) { // 1062 es el código de error para duplicados
		        $mensaje = "<span style='color: red;'>El DNI o el nombre de usuario ya está registrado, prueba con otro.</span>";
		    } else {
		        $mensaje = "<span style='color: red;'>Error con el formato de los datos introducidos, prueba con otros.</span>";
		    }
		}

		// Cerrar el statement
	    $stmt->close();
    }
}
            // Cerrar la conexión
            $conn->close();

            // Mostrar el mensaje
            echo $mensaje;
            ?>
        </div>
    </div>

    <nav>
        <a href="index.php">Inicio</a>
    </nav>

    <footer>
        <p>&copy; 2024 Página de Coches. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

