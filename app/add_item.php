<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self';");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Coche</title>
    <link rel="stylesheet" href="add_item.css">
    <script src="comprobaciones.js"></script>
</head>
<body>
    <h1>Agregar Coche</h1>
    <div class="container">
        <?php
        include 'db.php';  // Conectar a la base de datos

        // Inicializar variables para el formulario
        $nMatricula = '';
        $marcamodelo = '';
        $color = '';
        $kms = '';
        $cv = '';
        $anio = '';

        $error_message = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obtén los valores del formulario
            $nMatricula = filter_input(INPUT_POST, 'nMatricula', FILTER_SANITIZE_STRING);
    $marcamodelo = filter_input(INPUT_POST, 'marcamodelo', FILTER_SANITIZE_STRING);
    $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);
    $kms = filter_input(INPUT_POST, 'kms', FILTER_VALIDATE_INT);
    $cv = filter_input(INPUT_POST, 'cv', FILTER_VALIDATE_INT);
    $anio = filter_input(INPUT_POST, 'anio', FILTER_VALIDATE_INT);
   
   
    // Validar campos
    if (!preg_match('/^[0-9]{4}[A-Z]{3}$/', $nMatricula))
    {
        $error_message = 'La matrícula debe tener el formato de 4 números seguidos de 3 letras en mayúscula (ej. 1234ABC).';
    }
    elseif (!preg_match('/^[\p{L}\p{N}\s-]{1,30}$/u', $marcamodelo))
    {
        $error_message = 'Marca y modelo sólo puede contener letras, números y espacios.';
    }
    elseif (!preg_match('/^[\p{L}\s]{1,20}$/u', $color))
    {
        $error_message = 'Color sólo debe contener letras y espacios.';
    }
   
    else
    {
        // Usar una consulta preparada para insertar los datos en la base de datos
                $query = "INSERT INTO coches (matricula, marca_modelo, color, kilometros, CV, año) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);

                if ($stmt)
                {
                    // Ligar los parámetros a la consulta
                    // "sssiii" indica que los tres primeros son cadenas de texto y los tres últimos son enteros
                    $stmt->bind_param("sssiii", $nMatricula, $marcamodelo, $color, $kms, $cv, $anio);

                    // Ejecutar la consulta y verificar el resultado
                    if ($stmt->execute())
                    {
                        echo "<p style='color: green;'>Coche añadido correctamente.</p>";
                    }
                    else
                    {
                        if ($conn->errno === 1062)
                        {
                            $error_message = 'La matrícula ya está registrada, prueba con otra.';
                        }
                        else
                        {
                            $error_message = 'Error, prueba con otros datos.';
                        }
                    }

                    // Cerrar el statement
                    $stmt->close();
                }
                else
                {
                    $error_message = 'Error al preparar la consulta.';
                }
        }
    }
       
        // Mostrar mensaje de error si existe
        if ($error_message) {
            echo "<p style='color: red;'>" . htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') . "</p>";
        }

        // Formulario para agregar nuevos datos
        echo '<form id="item_add_form" action="add_item.php" method="post" onsubmit="return validarMatricula();">';
        echo '<label for="nMatricula">Matrícula:</label>';
        echo '<input type="text" id="nMatricula" name="nMatricula" value="' . htmlspecialchars(strip_tags($nMatricula), ENT_QUOTES, 'UTF-8') . '" required>';
        echo '<label for="marcamodelo">Marca y modelo:</label>';
        echo '<input type="text" id="marcamodelo" name="marcamodelo" value="' . htmlspecialchars(strip_tags($marcamodelo), ENT_QUOTES, 'UTF-8') . '" required>';
        echo '<label for="color">Color:</label>';
        echo '<input type="text" id="color" name="color" value="' . htmlspecialchars(strip_tags($color), ENT_QUOTES, 'UTF-8') . '" required>';
        echo '<label for="kms">Kilómetros:</label>';
        echo '<input type="number" id="kms" name="kms" value="' . htmlspecialchars(strip_tags($kms), ENT_QUOTES, 'UTF-8') . '" required>';
        echo '<label for="cv">Caballos:</label>';
        echo '<input type="number" id="cv" name="cv" value="' . htmlspecialchars(strip_tags($cv), ENT_QUOTES, 'UTF-8') . '" required>';
        echo '<label for="anio">Año:</label>';
        echo '<input type="number" id="anio" name="anio" value="' . htmlspecialchars(strip_tags($anio), ENT_QUOTES, 'UTF-8') . '" required>';
        echo '<button id="item_add_submit" name="item_add_submit" type="submit">Agregar Coche</button>';
        echo '</form>';
        ?>
    </div>

    <nav>
        <a href="index.php">Inicio</a>
    </nav>

    <footer>
        <p>&copy; 2024 Página de Coches. Todos los derechos reservados.</p>
    </footer>
   
</body>
</html>

