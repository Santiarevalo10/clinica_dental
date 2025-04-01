<?php
$host = "clinica-dental-server.mysql.database.azure.com";
$db = "clinica-dental";
$user = "xbppwhznfp";
$pass = "9k\$WTVDlyK\$gXdHL";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // No requiere archivo de certificado
            PDO::MYSQL_ATTR_SSL_CA => null, // Usar el certificado interno de Azure
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
