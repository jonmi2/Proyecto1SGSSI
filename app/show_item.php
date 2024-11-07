<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self';");
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

    $item = $_GET['item'];
    $query = mysqli_query($conn, "SELECT * FROM coches WHERE matricula='$item'");

    if ($row = mysqli_fetch_assoc($query)) {
        echo "<p><strong>Marca y Modelo:</strong> " . htmlspecialchars($row['marca_modelo'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Matrícula:</strong> " . htmlspecialchars($row['matricula'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Color:</strong> " . htmlspecialchars($row['color'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Kilómetros:</strong> " . htmlspecialchars($row['kilometros'], ENT_QUOTES, 'UTF-8') . " km</p>";
        echo "<p><strong>Caballos de fuerza (CV):</strong> " . htmlspecialchars($row['CV'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Año:</strong> " . htmlspecialchars($row['año'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "</div>";
        } else {
            echo "<p class='not-found'>Coche no encontrado.</p>";
        }
    ?>
    <a href="items.php" class="back-link">Volver a la lista de coches</a>
    </div>

    <footer>
        <p>&copy; 2024 Página de Coches. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
