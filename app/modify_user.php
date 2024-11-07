<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario</title>
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
    <h1>Modificar Usuario</h1>

    <div class="container">
        <?php
        include 'db.php';  // Conectar a la base de datos

        // Verificar que la conexión a la base de datos sea válida
        if ($conn === null) {
            die("No se pudo establecer la conexión a la base de datos.");
        }

        // Obtener el usuario a modificar
        // Decodificar y filtrar el parámetro `user`
        $user = mysqli_real_escape_string($conn, $_GET['user']);

        // Variable para controlar el mensaje de error
        $error_message = '';

        // Manejo del envío del formulario
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obtener los datos del formulario
            $nombreApellidos = mysqli_real_escape_string($conn, $_POST['nombre_apellidos']);
            $dni = mysqli_real_escape_string($conn, $_POST['dni']);
            $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
            $fechaNacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']);
            $email = mysqli_real_escape_string($conn, $_POST['mail']);
            $username = mysqli_real_escape_string($conn, $_POST['username']);

            // Actualizar los datos en la base de datos
            $sql = "UPDATE usuarios SET nombre_apellidos='$nombreApellidos', dni='$dni', telefono='$telefono', fecha_nacimiento='$fechaNacimiento', email='$email' WHERE username='$username'";
            
            if (mysqli_query($conn, $sql)) {
                // Mostrar mensaje de éxito
                echo "<p style='color: green;'>Cambios guardados correctamente.</p>";
            } else {
                if ($conn->errno === 1062) { // 1062 es el código de error para duplicados
                    $error_message = 'DNI ya está registrado, prueba con otro.';
                } else {
                    $error_message = 'Error, mete otros datos.';
                }
            }
        }

        // Consulta para obtener los datos del usuario
        $query = mysqli_query($conn, "SELECT * FROM usuarios WHERE username='$user'");
        
        // Manejo de errores en la consulta
        if (!$query) {
            die("Error en la consulta: " . mysqli_error($conn));
        }

        $row = mysqli_fetch_assoc($query);

        // Comprobar si se encontró el usuario
        if ($row) {
            // Mostrar el mensaje de error si existe
            if ($error_message) {
    		echo "<p style='color: red;'>" . htmlspecialchars($error_message) . "</p>";
		}


            // Formulario para modificar datos del usuario
            echo '<form id="user_modify_form" action="modify_user.php?user=' . urlencode($user) . '" method="post" onsubmit="return validarFormulario();">';
            echo '<label for="nombre_apellidos">Nombre y apellidos:</label>';
            echo '<input type="text" id="nombre_apellidos" name="nombre_apellidos" value="' . htmlspecialchars($row['nombre_apellidos'], ENT_QUOTES, 'UTF-8') . '" required>'; 
            echo '<label for="dni">DNI:</label>';
            echo '<input type="text" id="dni" name="dni" value="' . htmlspecialchars($row['dni'], ENT_QUOTES, 'UTF-8') . '" required>'; 
            echo '<label for="telefono">Teléfono:</label>';
            echo '<input type="text" id="telefono" name="telefono" value="' . htmlspecialchars($row['telefono'], ENT_QUOTES, 'UTF-8') . '" required>'; 
            echo '<label for="fecha_nacimiento">Fecha de Nacimiento:</label>';
            echo '<input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="' . htmlspecialchars($row['fecha_nacimiento'], ENT_QUOTES, 'UTF-8') . '" required>'; 
            echo '<label for="mail">Email:</label>';
            echo '<input type="email" id="mail" name="mail" value="' . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . '" required>'; 
            echo '<input type="hidden" name="username" value="' . htmlspecialchars($user, ENT_QUOTES, 'UTF-8') . '">';
            echo '<button id="user_modify_submit" type="submit">Guardar Cambios</button>';
            echo '</form>';
        } else {
            echo "<p>Usuario no encontrado.</p>";
        }
        ?>
    </div>
    
    <nav>
        <a href="show_user.php?user=<?php echo urlencode(htmlspecialchars($username)); ?>">Volver</a>
    </nav>

    <footer>
        <p>&copy; 2024 Página de Coches. Todos los derechos reservados.</p>
    </footer>
    
    <?php
    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
    ?>
</body>
</html>

