<?php
// Archivo: ./Conexion/conexion.php
hostname=clinica-dental-server.mysql.database.azure.com
port=3306
username=xbppwhznfp
password=9k$WTVDlyK$gXdHL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
    die(); 
}
?>
