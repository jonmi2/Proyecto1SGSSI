<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self';");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Item</title>
    <link rel="stylesheet" href="modify_item.css">
    <script src="comprobaciones.js"></script>
</head>
<body>
    <h1>Modificar Coche</h1>
    <div class="container">
        <?php
        include 'db.php';  // Conectar a la base de datos

        $item = $_GET['item'];
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
            // Obtén los valores del formulario
            $matricula = $_POST['matricula'];
            $nMatricula = $_POST['nMatricula'];
            $marcamodelo = $_POST['marcamodelo'];
            $color = $_POST['color'];
            $kms = $_POST['kms'];
            $cv = $_POST['cv'];
            $anio = $_POST['anio'];

            // Ejecuta la consulta de actualización
            $query = "UPDATE coches SET matricula = ?, marca_modelo = ?, color = ?, kilometros = ?, CV = ?, 	año = ? WHERE matricula = ?";
    	    $stmt = $conn->prepare($query);

            if ($stmt === false) {
        echo "<p style='color: red;'>Error en la preparación de la consulta de actualización.</p>";
    } else {
        // Enlazar los parámetros
        $stmt->bind_param("sssssis", $nMatricula, $marcamodelo, $color, $kms, $cv, $anio, $matricula);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "<p style='color: green;'>Cambios guardados correctamente.</p>";
        } else {
            // Manejo de errores
            if ($stmt->errno === 1062) { // Código de error para duplicados
                $error_message = 'La matrícula ya está registrada, prueba con otra.';
            } else {
                $error_message = 'Error, prueba con otros datos.';
            }
        }

        // Cerrar el statement
        $stmt->close();
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

	// Mostrar mensaje de error si existe
	if ($error_message) {
	    echo "<p style='color: red;'>" . htmlspecialchars($error_message) . "</p>";
	}

        // Formulario para cambiar datos
        echo '<form id="item_modify_form" action="modify_item.php?item=' . urlencode($item) . '" method="post">';
        echo '<label for="nMatricula">Nueva matrícula:</label>';
        echo '<input type="text" id="nMatricula" name="nMatricula" value="' . htmlspecialchars($nMatricula, ENT_QUOTES, 'UTF-8') . '" required>';
        echo '<label for="marcamodelo">Marca y modelo:</label>';
        echo '<input type="text" id="marcamodelo" name="marcamodelo" value="' . htmlspecialchars($marcamodelo, ENT_QUOTES, 'UTF-8') . '" required>';
        echo '<label for="color">Color:</label>';
        echo '<input type="text" id="color" name="color" value="' . htmlspecialchars($color, ENT_QUOTES, 'UTF-8') . '" required>';
        echo '<label for="kms">Kilómetros:</label>';
        echo '<input type="text" id="kms" name="kms" value="' . htmlspecialchars($kms, ENT_QUOTES, 'UTF-8') . '" required>';
        echo '<label for="cv">Caballos:</label>';
        echo '<input type="text" id="cv" name="cv" value="' . htmlspecialchars($cv, ENT_QUOTES, 'UTF-8') . '" required>';
        echo '<label for="anio">Año:</label>';
        echo '<input type="text" id="anio" name="anio" value="' . htmlspecialchars($anio, ENT_QUOTES, 'UTF-8') . '" required>';
        echo '<input type="hidden" name="matricula" value="' . htmlspecialchars($item, ENT_QUOTES, 'UTF-8') . '">';
        echo '<button id="item_modify_submit" name="item_modify_submit" type="submit">Guardar Cambios</button>';
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

