<?php
// config.php - Archivo para la configuración de la base de datos

// Datos de conexión a la base de datos
$host = 'localhost';  // Cambia esto si usas otro servidor o IP para la base de datos
$dbname = 'las_coloradas';  // Nombre de la base de datos
$user = 'root';  // Usuario de la base de datos
$password = '';  // Contraseña de la base de datos (puedes poner la contraseña si la tienes configurada)

try {
    // Establecer la conexión a la base de datos usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    
    // Configurar el manejo de errores de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Establecer el modo de caracteres a UTF-8 para evitar problemas con caracteres especiales
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    // Si ocurre un error, mostrar el mensaje
    die("Error en la conexión a la base de datos: " . $e->getMessage());
}
?>
