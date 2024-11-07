<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline';");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Coche</title>
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
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .item-details {
            font-size: 18px;
            line-height: 1.6;
        }

        p {
            margin: 10px 0;
        }

        .not-found {
            color: red;
            font-weight: bold;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
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
    </style>
</head>
<body>
    <h1>Detalles del coche</h1>
    <div class="container">
    <?php
include 'db.php';  // Conectar a la base de datos

// Verificar si se recibió la matrícula en la URL
if (isset($_GET['item'])) {
    $item = $_GET['item'];

    // Preparar la consulta para obtener los datos del coche
    $stmt = $conn->prepare("SELECT * FROM coches WHERE matricula = ?");

    if ($stmt) {
        // Asociar el parámetro y ejecutarlo
        $stmt->bind_param('s', $item);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontraron resultados
        if ($row = $result->fetch_assoc()) {
            echo "<p><strong>Marca y Modelo:</strong> " . htmlspecialchars($row['marca_modelo'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p><strong>Matrícula:</strong> " . htmlspecialchars($row['matricula'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p><strong>Color:</strong> " . htmlspecialchars($row['color'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p><strong>Kilómetros:</strong> " . htmlspecialchars($row['kilometros'], ENT_QUOTES, 'UTF-8') . " km</p>";
            echo "<p><strong>Caballos de fuerza (CV):</strong> " . htmlspecialchars($row['CV'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p><strong>Año:</strong> " . htmlspecialchars($row['año'], ENT_QUOTES, 'UTF-8') . "</p>";
        } else {
            echo "<p class='not-found'>Coche no encontrado.</p>";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "<p>Error al preparar la consulta: " . $conn->error . "</p>";
    }
} else {
    echo "<p class='not-found'>No se especificó ningún coche.</p>";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

    <a href="items.php" class="back-link">Volver a la lista de coches</a>
    </div>

    <footer>
        <p>&copy; 2024 Página de Coches. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
