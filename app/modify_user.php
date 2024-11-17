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
    <title>Modificar Usuario</title>
    <link rel="stylesheet" href="modify_user.css">
    <script src="comprobaciones.js"></script>
</head>
<body>
    <h1>Modificar Usuario</h1>

    <div class="container">
        <<?php
include 'db.php';  // Conectar a la base de datos

// Verificar que la conexión a la base de datos sea válida
if ($conn === null) {
    die("No se pudo establecer la conexión a la base de datos.");
}

// Obtener el usuario a modificar de manera segura
$user = $_GET['user'];

// Variable para controlar el mensaje de error
$error_message = '';

// Manejo del envío del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombreApellidos = $_POST['nombre_apellidos'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $fechaNacimiento = $_POST['fecha_nacimiento'];
    $email = $_POST['mail'];
    $username = $_POST['username'];

    // Preparar la consulta para actualizar los datos en la base de datos
    $sql = "UPDATE usuarios SET nombre_apellidos = ?, dni = ?, telefono = ?, fecha_nacimiento = ?, email = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Asociar los parámetros
        $stmt->bind_param('ssssss', $nombreApellidos, $dni, $telefono, $fechaNacimiento, $email, $username);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Mostrar mensaje de éxito
            echo "<p style='color: green;'>Cambios guardados correctamente.</p>";
            log_mensaje("usuario modificado correctamente.");
        } else {
            if ($conn->errno === 1062) { // 1062 es el código de error para duplicados
                $error_message = 'DNI ya está registrado, prueba con otro.';
                log_mensaje("Intento de editar ususario fallido: DNI duplicado.");
            } else {
                $error_message = 'Error, mete otros datos.';
                log_mensaje("Intento de editar ususario fallido: Datos con formato erroneo.");
            }
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        $error_message = 'Error al preparar la consulta: ' . $conn->error;
    }
}

// Preparar la consulta para obtener los datos del usuario
$sql = "SELECT * FROM usuarios WHERE username = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Asociar el parámetro y ejecutar la consulta
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $result = $stmt->get_result();

    // Comprobar si se encontró el usuario
    if ($row = $result->fetch_assoc()) {
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
        echo '<div class="view-user-button-container">';
                echo '<a href="show_user.php?user=' . urlencode($user) . '"><button id="view_user_button" type="button">Volver a ver datos del usuario</button></a>';
                echo '</div>';
    } else {
        echo "<p>Usuario no encontrado.</p>";
    }
    
   

    // Cerrar la declaración
    $stmt->close();
} else {
    die("Error al preparar la consulta: " . $conn->error);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

</body>
</html>

