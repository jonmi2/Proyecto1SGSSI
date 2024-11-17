<?php
ini_set('session.cookie_httponly', 1);
session_start(); // Asegúrate de iniciar la sesión
header("Content-Security-Policy: default-src 'none'; script-src 'self'; connect-src 'self'; img-src 'self'; style-src 'self';base-uri 'self';form-action 'self'");
header("X-Frame-Options: SAMEORIGIN");
include 'logs.php';

// Paso 1: Generar el Token CSRF y Guardarlo en la Sesión
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Token aleatorio de 32 bytes
}
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

        // Paso 3: Verificar el Token CSRF en el Servidor
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_delete_submit'])) {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die("Error: CSRF token inválido.");
            }

            // Eliminar el token CSRF de la sesión (Paso 4)
            unset($_SESSION['csrf_token']); // Elimina el token CSRF de la sesión

            if ($_POST['item_delete_submit'] == 'yes') {
                // Preparar la consulta para borrar el coche
                $stmt = $conn->prepare("DELETE FROM coches WHERE matricula = ?");
                
                if ($stmt) {
                    // Asociar el parámetro y ejecutarlo
                    $stmt->bind_param('s', $matricula);
                    
                    if ($stmt->execute()) {
                        echo "<p>El coche con matrícula: '" . htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8') . "' ha sido eliminado con éxito.</p>";
                        log_mensaje("Coche con matrícula " . htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8') . "ha sido eliminado con éxito.");
                    } else {
                        echo "<p>Error al eliminar el coche.</p>";
                        log_mensaje("Error al eliminar el coche con matricula " . htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8') . " .");

                    }

                    // Cerrar la declaración
                    $stmt->close();
                } else {
                    echo "<p>Error al preparar la consulta: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>No se ha borrado nada.</p>";
                log_mensaje("Coche con matrícula  '" . htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8') . "' ha decidido no eliminarse.");

            }
            echo "<a href='items.php'>Volver a la lista de Coches</a>";
        } else {
            // Paso 2: Incluir el Token CSRF en el Formulario HTML
            // Mostrar el mensaje de confirmación antes de eliminar
            echo "<p>¿Deseas borrar el coche con matrícula: '" . htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8') . "'? En caso de hacerlo no hay vuelta atrás.</p>";
            echo "<form method='POST' action=''>
                    <input type='hidden' name='csrf_token' value='" . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . "'>
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

