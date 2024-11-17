<?php
// Incluir el archivo de conexión a la base de datos
include('db.php');
include 'logs.php';
ini_set('session.cookie_httponly', 1);
session_start(); // Iniciar sesión para manejar el token CSRF

// Paso 1: Generar el Token CSRF y Guardarlo en la Sesión
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token CSRF único
}

header("Content-Security-Policy: default-src 'none'; script-src 'self'; connect-src 'self'; img-src 'self'; style-src 'self';base-uri 'self';form-action 'self'");
header("X-Frame-Options: SAMEORIGIN");

// Inicializar variable en caso de que haya un error
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Paso 3: Verificar el Token CSRF en el Servidor
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error: CSRF token inválido.");
    }

    // Eliminar el token CSRF después de su validación (Paso 4)
    unset($_SESSION['csrf_token']); // Eliminar el token de la sesión

    // Obtener el nombre de usuario y la contraseña desde el formulario
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Consulta SQL para buscar al usuario
    $stmt = $conn->prepare("SELECT username, password FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si el usuario existe
    if (mysqli_num_rows($result) > 0) {
        // Obtener el registro del usuario (asi encontramos la contra buscando por el numero de fila)
        $row = mysqli_fetch_assoc($result);
        
        // Paso 5: Verificar la contraseña utilizando password_verify()
        if (password_verify($password, $row['password'])) {         
            // Inicio de sesión exitoso
            header("Location: show_user.php?user=" . urlencode($username));
            log_mensaje("Intento de logearse correcto de usuario ". htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . ".");
        } else {
            $error = "Contraseña incorrecta";
            log_mensaje("Intento de logearse incorrecto de usuario ". htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . " por contraseña incorrecta.");
        }
    } else {
        $error = "Usuario no encontrado";
        log_mensaje("Intento de logearse en un usuario inexistente.");
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <h1>Iniciar Sesión</h1>

    <div class="container">
        <form id="login_form" action="login.php" method="post">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <!-- Paso 2: Incluir el Token CSRF en el Formulario HTML -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <button id="login_submit" type="submit">Iniciar Sesión</button>
        </form>

        <!-- Mostrar errores en caso de haberlos en ROJO -->
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
    </div>

    <nav>
        <a href="index.php">Inicio</a>
    </nav>

    <footer>
        <p>&copy; Ander-Iker-Jon-Andoni-Mikel-Asier </p>
    </footer>
</body>
</html>
