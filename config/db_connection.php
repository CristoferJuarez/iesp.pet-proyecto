<?php
/**
 * Conexión a la Base de Datos con PDO
 */

$host = 'localhost';
$dbname = 'refugio_patitas';
$username = 'root';
$password = ''; // Por defecto
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    error_log("Error de conexión a la base de datos: " . $e->getMessage());
    die("Error al conectar con la base de datos del refugio. Por favor, asegúrese de tener activado MySQL en XAMPP.");
}
?>
