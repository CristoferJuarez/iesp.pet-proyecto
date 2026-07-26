<?php
/**
 * Configuración Global del Proyecto
 */

// Iniciamos la sesión de PHP de forma global
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definimos la URL base del proyecto en nuestro entorno local de XAMPP
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/IESP.PET-PROYECTO/');
}

// Puedes añadir más constantes globales aquí en el futuro si lo necesitas
// Por ejemplo, el nombre de la organización ficticia
if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'Refugio Patitas Trujillo');
}

// Incluimos la conexión a la base de datos de forma global
require_once __DIR__ . '/db_connection.php';