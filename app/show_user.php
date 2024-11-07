<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self';");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Usuario</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 800px; /* Puedes ajustar el ancho máximo según tus necesidades */
            margin: 0 auto; /* Esto centra el contenedor horizontalmente */
            padding: 20px; /* Añade un poco de espacio interno */
        }

        h1 {
            text-align: center;
            color: #444;
            padding: 20px;
            background-color: #fff;
            margin: 0;
            border-bottom: 2px solid #ddd;
        }

        nav {
            display: flex;
            justify-content: center;
            background-color: #007BFF;
            padding: 15px;
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

        .content {
            text-align: center;
            padding: 40px;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 2px solid #ddd;
            margin-top: 40px;
            color: #777;
        }

        footer p {
            margin: 0;
        }
        
        a.button {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px; /* Espaciado entre botones */
            text-align: center; /* Centra el texto dentro del botón */
        }

        .btn-primary {
            background-color: #007BFF;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-success {
            background-color: #28A745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        /* Nuevo estilo para el contenedor de botones */
        .button-container {
            display: flex;
            justify-content: center; /* Centra horizontalmente */
            margin-top: 20px; /* Espaciado superior para los botones */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalles del Usuario</h1>
        <?php
        include 'db.php';  // Conectar a la base de datos

        $user = $_GET['user'];
        $query = mysqli_query($conn, "SELECT * FROM usuarios WHERE username='$user'");

        if ($row = mysqli_fetch_assoc($query)) {
            echo "<div class='content'>";
            echo "<p>Nombre: " . htmlspecialchars($row['nombre_apellidos'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p>DNI: " . htmlspecialchars($row['dni'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p>Teléfono: " . htmlspecialchars($row['telefono'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p>Fecha de Nacimiento: " . htmlspecialchars($row['fecha_nacimiento'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p>Email: " . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "</div>";
        } else {
            echo "<div class='content'><p>Usuario no encontrado.</p></div>";
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conn);    
        ?>
        <!-- Contenedor para los botones -->
        <div class="button-container">
            <a href="index.php" class="button btn-primary">Inicio (se cerrará la sesión del usuario)</a>
            <a href="http://localhost:81/modify_user.php?user=<?php echo urlencode($user); ?>" class="button btn-success">Modificar datos</a>

        </div>
    </div>
</body>
</html>


