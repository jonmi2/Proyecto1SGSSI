<?php
function log_mensaje($message)
{
    // Define la ubicación y el nombre del archivo de log
    $directorio = '/var/tmp';  // Directorio donde se guardará el archivo
    $archivo = $directorio . '/logs.log';  // El archivo de log completo
    
    // Asegúrate de que la carpeta exista
    if (!file_exists($directorio)) {
        mkdir($directorio, 0755, true);  // Crea el directorio si no existe
    }

    // Asegúrate de que el archivo de log exista, si no, lo crea
    if (!file_exists($archivo)) {
        touch($archivo);  // Crea el archivo de log si no existe
    }

    // Formato del mensaje: [Fecha y Hora] Mensaje
    $log_entry = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;

    // Escribir el mensaje en el archivo de log
    file_put_contents($archivo, $log_entry, FILE_APPEND | LOCK_EX); // Añadir al final del archivo
}
?>
