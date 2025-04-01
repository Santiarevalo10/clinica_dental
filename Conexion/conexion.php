<?php
// Archivo: ./Conexion/conexion.php
$host = "clinica-dental-server.mysql.database.azure.com";
$db = "clinica-dental";
$user = "xbppwhznfp";
$pass = "9k\$WTVDlyK\$gXdHL";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
