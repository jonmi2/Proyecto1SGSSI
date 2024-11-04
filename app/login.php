<?php
// Incluir el archivo de conexión a la base de datos
include('db.php');

// Inicializar variable en caso de que haya un error
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el nombre de usuario y la contraseña desde el formulario
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Consulta SQL para buscar al usuario
    $sql = "SELECT username, password FROM usuarios WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    // Verificar si el usuario existe
    if (mysqli_num_rows($result) > 0) {
        // Obtener el registro del usuario (asi encontramos la contra buscando por el numero de fila)
        $row = mysqli_fetch_assoc($result);
        
        // Verificar si la contraseña es correcta
         if ($password == $row['password']) {         
            //Inicio de sesión exitoso
            header("Location: show_user.php?user=" . urlencode($username));
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
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
</head>
<body>
    <h1>Iniciar Sesión</h1>

    <div class="container">
        <form id="login_form" action="login.php" method="post">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <button id="login_submit" type="submit">Iniciar Sesión</button>
        </form>
	<!-- Mostrar errores en caso de haberlos en ROJO -->
        <?php if ($error): ?>
    		<p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php endif; ?>

    </div>
	
    <nav>
        <a href="index.php">Inicio</a>
    </nav>
    <footer>
        <p>&copy; Ander-Iker-Jon-Andoni-Mikel-Asier </p>
    </footer>
</body>
</html>

