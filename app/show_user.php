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
    <title>Mostrar Usuario</title>
    <link rel="stylesheet" href="show_user.css">
</head>
<body>
    <div class="container">
        <h1>Detalles del Usuario</h1>
        <?php
	include 'db.php';  // Conectar a la base de datos

	// Verificar si se recibió el usuario en la URL
	if (isset($_GET['user'])) {
	    $user = $_GET['user'];

	    // Preparar la consulta para obtener los datos del usuario
	    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");

	    if ($stmt) {
		// Asociar el parámetro y ejecutarlo
		$stmt->bind_param('s', $user);
		$stmt->execute();
		$result = $stmt->get_result();

		// Verificar si se encontraron resultados
		if ($row = $result->fetch_assoc()) {
		    echo "<div class='content'>";
		    echo "<p>Nombre: " . htmlspecialchars($row['nombre_apellidos'], ENT_QUOTES, 'UTF-8') . "</p>";
		    echo "<p>DNI: " . htmlspecialchars($row['dni'], ENT_QUOTES, 'UTF-8') . "</p>";
		    echo "<p>Teléfono: " . htmlspecialchars($row['telefono'], ENT_QUOTES, 'UTF-8') . "</p>";
		    echo "<p>Fecha de Nacimiento: " . htmlspecialchars($row['fecha_nacimiento'], ENT_QUOTES, 'UTF-8') . "</p>";
		    echo "<p>Email: " . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . "</p>";
		    echo "</div>";
		    log_mensaje("Ver usuario con usermname".htmlspecialchars($user, ENT_QUOTES, 'UTF-8').".");
		} else {
		    echo "<div class='content'><p>Usuario no encontrado.</p></div>";
		}

		// Cerrar la declaración
		$stmt->close();
	    } else {
		echo "<p>Error al preparar la consulta: " . $conn->error . "</p>";
	    }
	} else {
	    echo "<div class='content'><p>No se especificó ningún usuario.</p></div>";
	}

	// Cerrar la conexión a la base de datos
	$conn->close();
?>

        <!-- Contenedor para los botones -->
        <div class="button-container">
            <a href="index.php" class="button btn-primary">Inicio (se cerrará la sesión del usuario)</a>
            <a href="http://localhost:81/modify_user.php?user=<?php echo urlencode($user); ?>" class="button btn-success">Modificar datos</a>

        </div>
    </div>
</body>
</html>


