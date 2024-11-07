<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Items</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #444;
            padding: 20px;
            background-color: #fff;
            border-bottom: 2px solid #ddd;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .actions {
            text-align: right;
            margin-bottom: 10px;
        }

        a {
            text-decoration: none;
            color: #007BFF;
        }

        a:hover {
            text-decoration: underline;
        }

        p {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .item-links a {
            margin-right: 15px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #218838;
        }

        /* Estilos para el menú de navegación */
        nav {
            text-align: center;
            background-color: #f8f9fa;
            padding: 15px 0;
            border-top: 2px solid #ddd;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            padding: 10px 20px;
            background-color: #0069d9;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #0056b3;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 2px solid #ddd;
            margin-top: 20px;
        }

        footer p {
            margin: 0;
            color: #777;
        }
    </style>

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

