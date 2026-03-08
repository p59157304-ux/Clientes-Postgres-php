<?php
/**
 * Adicionar Cliente a SQL Server
 * Servidor: DESKTOP-DII01CH\SQLEXPRESS
 * Autentificación: Windows
*/

$serverName = "DESKTOP-DII01CH\SQLEXPRESS"; 
$database = "udesdb"; 

// 1. Conexión segura usando PDO
try {
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database;Encrypt=true;TrustServerCertificate=true", null, null);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// 2. Verificar si se ha enviado el formulario
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nombres = $_POST['nombres'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $ci = $_POST['ci'];
    $direccion = $_POST['direccion'];
    
    try {
        // 3. Consulta SQL preparada para insertar datos (Seguridad contra SQL Injection)
        $sql = "INSERT INTO clientes (nombres, apellido_paterno, apellido_materno, ci, direccion) 
                VALUES (:nombres, :apellido_paterno, :apellido_materno, :ci, :direccion)";
        
        $stmt = $conn->prepare($sql);
        
        // 4. Vincular parámetros y ejecutar
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':apellido_paterno', $apellido_paterno);
        $stmt->bindParam(':apellido_materno', $apellido_materno);
        $stmt->bindParam(':ci', $ci);
        $stmt->bindParam(':direccion', $direccion);
        
        $stmt->execute();
        
        echo "<p style='color: green;'>Cliente agregado correctamente.</p>";
        
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error al insertar: " . $e->getMessage() . "</p>";
    }
}

// Cerrar conexión
$conn = null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Clientes</title>
    <link rel="stylesheet" href="misestilos.css">

</head>
<body>
    <h2>Adicionar Nuevo Cliente</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="nombres">Nombre:</label><br>
        <input type="text" id="nombres" name="nombres" required><br><br>

        <label for="apellido_paterno">Apellido Paterno:</label><br>
        <input type="text" id="apellido_paterno" name="apellido_paterno" required><br><br>

        <label for="apellido_materno">Apellido Materno:</label><br>
        <input type="text" id="apellido_materno" name="apellido_materno" required><br><br>

        <label for="ci">CI:</label><br>
        <input type="number" id="ci" name="ci" required><br><br>

        <label for="direccion">Dirección:</label><br>
        <input type="text" id="direccion" name="direccion" required><br><br>

        

        <input type="submit" values="Adicionar Cliente">
    </form>
    <br>
    <a href="MostrarClientes.php">Ver lista de Clientes</a>
</body>
</html>