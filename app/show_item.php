<?php

header("Content-Security-Policy: default-src 'none'; script-src 'self'; connect-src 'self'; img-src 'self'; style-src 'self';base-uri 'self';form-action 'self'");
header("X-Frame-Options: SAMEORIGIN");
include 'logs.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Coche</title>
    <link rel="stylesheet" href="show_item.css">
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
            log_mensaje("Ver coche con matricula".htmlspecialchars($row['matricula'], ENT_QUOTES, 'UTF-8').".");
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
