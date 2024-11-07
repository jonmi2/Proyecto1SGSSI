<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self';");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Coche</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh; /* Ocupa toda la altura de la pantalla */
        }

        h1 {
            width: 100%;
            text-align: center;
            color: #444;
            padding: 20px;
            background-color: #fff;
            margin: 0 0 20px 0; /* Espacio entre los elementos */
            border-bottom: 2px solid #ddd;
            flex-direction: column; /* Apilado en fila (horizontal) */
        }
        
        p {
            text-align: center;
            padding: 20px;
            margin: 20px 20px; /* Espacio entre los elementos */
            font-weight: bold;
            background-color: light-blue;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
            border-radius: 5px;
        }

        a, button {
            width: fit-content;
            text-align: center;
            padding: 10px;
            margin: 10px 20px; /* Espacio entre los elementos */
            font-weight: bold;
            background-color: #fff;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover, button:hover {
            background-color: #eee;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
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
    </style>
</head>
<body>
    <h1>Eliminar Coche</h1>

    <?php
    // Conexión a la base de datos
    $servername = "db";
    $username = "admin";  // Cambiar por tu usuario de MySQL
    $password = "test";   // Cambiar por tu contraseña de MySQL
    $dbname = "database"; // Nombre de tu base de datos

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener la matricula desde la URL
    if (isset($_GET['item'])) {
        $matricula = $_GET['item'];

        // Si se ha enviado el formulario para confirmar el borrado
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_delete_submit'])) {
            if ($_POST['item_delete_submit'] == 'yes') {
                // borrar el coche
                $query = mysqli_query($conn, "DELETE FROM coches WHERE matricula='$matricula'");

                if ($query) {
                    echo "<p>El coche con matrícula: '" . htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8') . "' ha sido eliminado con éxito.</p>";
                } else {
                    echo "<p>Error al eliminar el coche.</p>";
                }
            } else {
                echo "<p>No se ha borrado nada.</p>";
            }
            echo "<a href='items.php'>Volver a la lista de Coches</a>";
        } else {
            // Mostrar el mensaje de confirmación antes de eliminar
            echo "<p>¿Deseas borrar el coche con matrícula: '" . htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8') . "'? En caso de hacerlo no hay vuelta atrás.</p>";
            echo "<form method='POST' action=''>
                    <button type='submit' name='item_delete_submit' value='yes'>Sí</button>
                    <button type='submit' name='item_delete_submit' value='no'>No</button>
                  </form>";
        }
    } else {
        echo "<p>No se ha especificado ningún coche para eliminar.</p>";
        echo "<a href='items.php'>Volver a la lista de coches</a>";
    }

    // Cerrar la conexión
    mysqli_close($conn);
    ?>
</body>
</html>
