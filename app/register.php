<?php
ini_set('session.cookie_httponly', 1);
session_start(); // Iniciar sesión para manejar el token CSRF

// Paso 1: Generar el Token CSRF y Guardarlo en la Sesión cada vez que se carga la página
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token CSRF único
}

header("Content-Security-Policy: default-src 'none'; script-src 'self'; connect-src 'self'; img-src 'self'; style-src 'self';base-uri 'self';form-action 'self'");
header("X-Frame-Options: SAMEORIGIN");
include 'logs.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="register.css">
    <script src="comprobaciones.js"></script>
</head>
<body>
    <h1>Registro</h1>

    <div class="container">
        <form id="register_form" action="register.php" method="POST" onsubmit="return validarFormulario();">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" placeholder="Ejemplo: Juan123" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required>

            <label for="mail">Correo Electrónico:</label>
            <input type="email" id="mail" name="mail" placeholder="Ejemplo: nombre@servidor.com" required>

            <label for="nombre_apellidos">Nombre y Apellidos:</label>
            <input type="text" id="nombre_apellidos" name="nombre_apellidos" placeholder="Ejemplo: Juan Pérez Gómez" required>

            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" placeholder="Ejemplo: 12345678Z" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" placeholder="Ejemplo: 600123456" required>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>

            <!-- Paso 2: Incluir el Token CSRF en el Formulario HTML -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <button id="register_submit" name="submit" type="submit">Registrarse</button>
        </form>

        <!-- Contenedor para mostrar mensajes -->
        <div id="mensaje" class="mensaje">
            <?php
            // Inicializar la variable de mensaje
            $mensaje = '';

            // Conexión a la base de datos
            $servername = "db";
            $username_db = "admin";  // Cambiar por tu usuario de MySQL
            $password_db = "test";      // Cambiar por tu contraseña de MySQL
            $dbname = "database";  // Nombre de tu base de datos

            // Crear conexión
            $conn = new mysqli($servername, $username_db, $password_db, $dbname);

            // Verificar conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Paso 3: Verificar el Token CSRF en el Servidor
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    die("Error: CSRF token inválido.");
                }

                // Eliminar el token CSRF después de su validación (para seguridad adicional)
                unset($_SESSION['csrf_token']);

                // Obtener datos del formulario
                $username = $_POST['username'];
                $nombre_apellidos = $_POST['nombre_apellidos'];
                $email = $_POST['mail'];
                $password = $_POST['password'];
                $dni = $_POST['dni'];
                $telefono = $_POST['telefono'];
                $fecha_nacimiento = $_POST['fecha_nacimiento'];

                // Encriptar la contraseña
    		$password_hash = password_hash($password, PASSWORD_BCRYPT);  // Encriptación de la contraseña
                
                $sql = "INSERT INTO usuarios (nombre_apellidos, dni, telefono, fecha_nacimiento, email, username, password) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt = $conn->prepare($sql);

                // Verificar si se preparó correctamente
                if ($stmt === false) {
                    $mensaje = "<span style='color: red;'>Error en la preparación de la consulta.</span>";
                } else {
                    // Enlazar los parámetros (s indica que todos son strings)
                    $stmt->bind_param("sssssss", $nombre_apellidos, $dni, $telefono, $fecha_nacimiento, $email, $username, $password_hash);

                    // Ejecutar la consulta
                    if ($stmt->execute()) {
                        $mensaje = "<span style='color: green;'>Registro exitoso.</span>"; // Mensaje de éxito
                        log_mensaje("Usuario registrado correctamente");
                    } else {
                        // Manejo de errores
                        if ($stmt->errno === 1062) { // 1062 es el código de error para duplicados
                            $mensaje = "<span style='color: red;'>El DNI o el nombre de usuario ya está registrado, prueba con otro.</span>";
                            log_mensaje("Intento de registro de usuario incorrecto: DNI duplicado");
                        } else {
                            $mensaje = "<span style='color: red;'>Error con el formato de los datos introducidos, prueba con otros .</span>";
                            log_mensaje("Intento de registro de usuario incorrecto: formato de datos incorrectos " . $stmt->error);
                        }
                    }

                    // Cerrar el statement
                    $stmt->close();
                }
            }

            // Cerrar la conexión
            $conn->close();

            // Mostrar el mensaje
            echo $mensaje;
            ?>
        </div>
    </div>

    <nav>
        <a href="index.php">Inicio</a>
    </nav>

    <footer>
        <p>&copy; 2024 Página de Coches. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

