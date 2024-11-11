<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self';");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Coche</title>
    <link rel="stylesheet" href="delete_item.css">
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

// Obtener la matrícula desde la URL
if (isset($_GET['item'])) {
    $matricula = $_GET['item'];

    // Si se ha enviado el formulario para confirmar el borrado
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_delete_submit'])) {
        if ($_POST['item_delete_submit'] == 'yes') {
            // Preparar la consulta para borrar el coche
            $stmt = $conn->prepare("DELETE FROM coches WHERE matricula = ?");
            
            if ($stmt) {
                // Asociar el parámetro y ejecutarlo
                $stmt->bind_param('s', $matricula);
                
                if ($stmt->execute()) {
                    echo "<p>El coche con matrícula: '" . htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8') . "' ha sido eliminado con éxito.</p>";
                } else {
                    echo "<p>Error al eliminar el coche.</p>";
                }

                // Cerrar la declaración
                $stmt->close();
            } else {
                echo "<p>Error al preparar la consulta: " . $conn->error . "</p>";
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
$conn->close();
?>

</body>
</html>
