<?php
// Obtén las credenciales desde las variables de entorno
$hostname = "db";  // El nombre del servicio de la base de datos en docker-compose
$username = getenv('MYSQL_USER');    // MYSQL_USER desde el archivo .env
$password = getenv('MYSQL_PASSWORD'); // MYSQL_PASSWORD desde el archivo .env
$db = getenv('MYSQL_DATABASE');      // MYSQL_DATABASE desde el archivo .env

// Conecta a la base de datos
$conn = mysqli_connect($hostname, $username, $password, $db);

// Verifica si la conexión fue exitosa
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
