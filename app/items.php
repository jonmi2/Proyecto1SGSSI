<?php
header("Content-Security-Policy: default-src 'none'; script-src 'self'; connect-src 'self'; img-src 'self'; style-src 'self';base-uri 'self';form-action 'self'");
header("X-Frame-Options: SAMEORIGIN");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Items</title>
    <link rel="stylesheet" href="items.css">

</head>
<body>
    <h1>Gestión de Items</h1>
    <div class="container">
        <div class="actions">
            <a href="add_item.php" class="button">Agregar Item</a>
        </div>
        
        <?php
        include 'db.php';  // Conectar a la base de datos

        $query = mysqli_query($conn, "SELECT * FROM coches");  // Asumiendo que existe una tabla de coches

        while ($row = mysqli_fetch_assoc($query)) {
            // Usar htmlspecialchars para escapar la matrícula y el modelo
            $matricula = htmlspecialchars($row['matricula'], ENT_QUOTES, 'UTF-8');
            $marca_modelo = htmlspecialchars($row['marca_modelo'], ENT_QUOTES, 'UTF-8');

            echo "<p>Matricula: <strong>{$matricula}</strong> - Marca_modelo: <strong>{$marca_modelo}</strong>
            <span class='item-links'>
                <a href='show_item.php?item={$matricula}'>Ver</a>
                <a href='modify_item.php?item={$matricula}&model={$marca_modelo}'>Modificar</a>
                <a href='delete_item.php?item={$matricula}'>Eliminar</a>
            </span></p>";
        }
        ?>
    </div>
    
    <!-- Menú de navegación -->
    <nav>
        <a href="index.php">Inicio</a>
        <a href="register.php">Registro</a>
        <a href="login.php">Login</a>
    </nav>

<!-- Footer -->
    <footer>
        <p>&copy; 2024 Gestión de Items</p>
    </footer>
</body>
</html>

