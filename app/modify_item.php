<?php
header("Content-Security-Policy: default-src 'none'; script-src 'self'; connect-src 'self'; img-src 'self'; style-src 'self';base-uri 'self';form-action 'self'");
header("X-Frame-Options: SAMEORIGIN");
include 'logs.php';
session_start(); // Iniciar sesión para manejar el token CSRF

// Paso 1: Generar el Token CSRF y Guardarlo en la Sesión
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token CSRF único
}

include 'db.php';  // Conectar a la base de datos

// Obtener y sanitizar el parámetro 'item' de la URL
$item = filter_input(INPUT_GET, 'item', FILTER_SANITIZE_STRING);

if (!$item) {
    echo "<p style='color: red;'>ID de artículo no válido.</p>";
    exit;
}

// Consulta para obtener los datos actuales del coche
$stmt = $conn->prepare("SELECT * FROM coches WHERE matricula = ?");
$stmt->bind_param("s", $item);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

// Inicializar variable de error
$error_message = '';

// Inicializar variables para el formulario
$nMatricula = '';
$marcamodelo = '';
$color = '';
$kms = '';
$cv = '';
$anio = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Paso 3: Verificar el Token CSRF en el Servidor
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error: CSRF token inválido.");
    }

    // Eliminar el token CSRF después de su validación (Paso 4)
    unset($_SESSION['csrf_token']); // Eliminar el token de la sesión

    // Obtener los valores del formulario
    $matricula = $_POST['matricula'];
    $nMatricula = $_POST['nMatricula'];
    $marcamodelo = $_POST['marcamodelo'];
    $color = $_POST['color'];
    $kms = $_POST['kms'];
    $cv = $_POST['cv'];
    $anio = $_POST['anio'];

    // Validación de la entrada
    if (!preg_match('/^[0-9]{4}[A-Z]{3}$/', $nMatricula)) {
        $error_message = 'La matrícula debe tener el formato de 4 números seguidos de 3 letras en mayúscula (ej. 1234ABC).';
        log_mensaje("Intento de editar coche fallido por matricula incorrecta");
    }

    if (!preg_match('/^[\p{L}\p{N}\s-]{1,30}$/u', $marcamodelo)) {
        $error_message = 'Marca y modelo solo pueden contener letras, números y espacios.';
        log_mensaje("Intento de editar coche fallido por marcamodelo incorrecto");
    }

    if (!preg_match('/^[\p{L}\s]{1,20}$/u', $color)) {
        $error_message = 'El color solo debe contener letras y espacios.';
        log_mensaje("Intento de editar coche fallido por formato de color incorrecto");
    }

    if (!filter_var($kms, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0]]) ||
        !filter_var($cv, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0]]) ||
        !filter_var($anio, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1886]])) {
        $error_message = 'Kilómetros, caballos y año deben ser enteros positivos válidos. (kms y cv >0 y año > 1886)';
        log_mensaje("Intento de editar coche fallido por formato de kms o cv o año incorrecto");
    }

    // Si no hay errores, proceder con la actualización
    if ($error_message === '') {
        $query = "UPDATE coches SET matricula = ?, marca_modelo = ?, color = ?, kilometros = ?, CV = ?, año = ? WHERE matricula = ?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            echo "<p style='color: red;'>Error en la preparación de la consulta de actualización.</p>";
        } else {
            // Enlazar los parámetros
            $stmt->bind_param("sssssis", $nMatricula, $marcamodelo, $color, $kms, $cv, $anio, $matricula);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "<p style='color: green;'>Cambios guardados correctamente.</p>";
                log_mensaje("Coche editado");
            } else {
                // Manejo de errores
                if ($stmt->errno === 1062) { // Código de error para duplicados
                    $error_message = 'La matrícula ya está registrada, prueba con otra.';
                    log_mensaje("Intento de editar coche fallido por matricula duplicada");
                } else {
                    $error_message = 'Error, prueba con otros datos.';
                    log_mensaje("Intento de editar coche fallido por datos con formato incorrecto");
                }
            }

            // Cerrar el statement
            $stmt->close();
        }
    }
} else {
    // Si es un GET, carga los datos originales
    if ($row) {
        $nMatricula = $row['matricula'];
        $marcamodelo = $row['marca_modelo'];
        $color = $row['color'];
        $kms = $row['kilometros'];
        $cv = $row['CV'];
        $anio = $row['año'];
    } else {
        echo "<p>Item no encontrado.</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Coche</title>
    <link rel="stylesheet" href="modify_item.css"> <!-- Enlace al archivo CSS -->
</head>
<body>
    <h1>Modificar Coche</h1>
    <div class="container">
        <?php
        // Mostrar mensaje de error si existe
        if ($error_message) {
            echo "<p style='color: red;'>" . htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') . "</p>";
        }
        ?>

        <!-- Formulario para cambiar datos -->
        <form id="item_modify_form" action="modify_item.php?item=<?php echo urlencode($item); ?>" method="post">
            <label for="nMatricula">Nueva matrícula:</label>
            <input type="text" id="nMatricula" name="nMatricula" value="<?php echo htmlspecialchars($nMatricula, ENT_QUOTES, 'UTF-8'); ?>" required>
            
            <label for="marcamodelo">Marca y modelo:</label>
            <input type="text" id="marcamodelo" name="marcamodelo" value="<?php echo htmlspecialchars($marcamodelo, ENT_QUOTES, 'UTF-8'); ?>" required>
            
            <label for="color">Color:</label>
            <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($color, ENT_QUOTES, 'UTF-8'); ?>" required>
            
            <label for="kms">Kilómetros:</label>
            <input type="text" id="kms" name="kms" value="<?php echo htmlspecialchars($kms, ENT_QUOTES, 'UTF-8'); ?>" required>
            
            <label for="cv">Caballos:</label>
            <input type="text" id="cv" name="cv" value="<?php echo htmlspecialchars($cv, ENT_QUOTES, 'UTF-8'); ?>" required>
            
            <label for="anio">Año:</label>
            <input type="text" id="anio" name="anio" value="<?php echo htmlspecialchars($anio, ENT_QUOTES, 'UTF-8'); ?>" required>
            
            <input type="hidden" name="matricula" value="<?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>"> <!-- Paso 2: Incluir el Token CSRF -->
            <button id="item_modify_submit" name="item_modify_submit" type="submit">Guardar Cambios</button>
        </form>
    </div>

    <nav>
        <a href="index.php">Inicio</a>
    </nav>

    <footer>
        <p>&copy; 2024 Página de Coches. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

