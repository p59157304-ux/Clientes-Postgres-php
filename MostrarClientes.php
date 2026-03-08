<?php
/**
 * Conexion a SQL Server
 * Servidor: DESKTOP-DII01CH\SQLEXPRESS
 * Autentificación: Windows
*/

$serverName = "DESKTOP-DII01CH\SQLEXPRESS"; 
$database = "udesdb"; 

try {
    // Conexión usando PDO
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database;Encrypt=true;TrustServerCertificate=true", null, null);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // --- CONSULTA ---
    $query = "SELECT id_clientes, nombres, apellido_paterno, apellido_materno, ci, direccion 
              FROM clientes 
              ORDER BY id_clientes ASC";
    
    $resultado = $conn->query($query);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Clientes</title>
    <link rel="stylesheet" href="misestilos.css">
</head>
<body>
    <h2>Listado de Clientes</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>CI</th>
            <th>Dirección</th> 
        </tr> 

        <?php
        // Recorremos los resultados usando PDO
        while($fila = $resultado->fetch(PDO::FETCH_ASSOC)){
            echo "<tr>";
            echo "<td>".$fila['id_clientes']."</td>";
            echo "<td>".$fila['nombres']."</td>";
            echo "<td>".$fila['apellido_paterno']."</td>";
            echo "<td>".$fila['apellido_materno']."</td>";
            echo "<td>".$fila['ci']."</td>";
            echo "<td>".$fila['direccion']."</td>";
            echo "</tr>";
        }
        ?> 
    </table> 
</body> 
</html>

<?php
// Cerrar conexión PDO
$conn = null;
?>