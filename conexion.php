<?php
/**
 * Conexion a SQL Server
 * Servidor: DESKTOP-DII01CH\SQLEXPRESS
 * Autentificación: Windows
*/

$serverName = "DESKTOP-DII01CH\SQLEXPRESS"; 
$database = "udesdb"; 

try {
    
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database;Encrypt=true;TrustServerCertificate=true", null, null);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "";
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>