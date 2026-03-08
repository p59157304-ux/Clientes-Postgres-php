<?php
/**
 * Modificar Clientes en SQL Server
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

$mostrar_lista = true;
$mensaje = "";
$cliente = null;

// 2. Lógica de Actualización 
if (isset($_POST['guardar'])) {
    $id = $_POST['id_clientes'];
    $nombres = $_POST['nombres'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $ci = $_POST['ci'];
    $direccion = $_POST['direccion'];

    // Consulta preparada para actualizar
    $sql_update = "UPDATE clientes SET nombres = :nombres, apellido_paterno = :ap, apellido_materno = :am, ci = :ci, direccion = :dir WHERE id_clientes = :id";
    
    try {
        $stmt = $conn->prepare($sql_update);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':ap', $apellido_paterno);
        $stmt->bindParam(':am', $apellido_materno);
        $stmt->bindParam(':ci', $ci);
        $stmt->bindParam(':dir', $direccion);
        $stmt->bindParam(':id', $id);
        
        $stmt->execute();
        $mensaje = "<p style='color: green;'>Cliente Actualizado Correctamente.</p>";
    } catch (PDOException $e) {
        $mensaje = "<p style='color: red;'>Error al actualizar: " . $e->getMessage() . "</p>";
    }
}

// 3. Lógica para mostrar el formulario de edición
if (isset($_POST['modificar'])) {
    if(isset($_POST['ids']) && count($_POST['ids']) == 1) {
        $id_editar = $_POST['ids'][0];

        $sql_buscar = "SELECT * FROM clientes WHERE id_clientes = :id";
        
        try {
            $stmt = $conn->prepare($sql_buscar);
            $stmt->bindParam(':id', $id_editar);
            $stmt->execute();
            
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($cliente) {
                $mostrar_lista = false; 
            } else {
                $mensaje = "<p style='color: red;'>Error al recuperar los datos del cliente.</p>";
            }
        } catch (PDOException $e) {
            $mensaje = "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        $mensaje = "<p style='color: orange;'>Por favor, seleccione exactamente un cliente para modificar.</p>";
    }       
}

// 4. Lógica del Buscador y Listado
$where = "";
$busqueda = "";
$parametros_lista = array();

if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $busqueda = $_GET['buscar'];
    
    $where = "WHERE LOWER(TRIM(nombres)) LIKE ? 
              OR LOWER(TRIM(apellido_paterno)) LIKE ? 
              OR LOWER(TRIM(apellido_materno)) LIKE ?";
    
    $param_valor = "%" . strtolower(trim($busqueda)) . "%";
    $parametros_lista = array($param_valor, $param_valor, $param_valor);
}

$sql_lista = "SELECT * FROM clientes $where ORDER BY id_clientes ASC";
$stmt_lista = $conn->prepare($sql_lista);
$stmt_lista->execute($parametros_lista);
$resultados = $stmt_lista->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Cliente</title>
    <link rel="stylesheet" href="misestilos.css">
</head>
<body>
    <h2>Modificar Cliente</h2>

    <?php if ($mensaje): ?>
        <div style="padding: 10px; margin-bottom: 10px;"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if (!$mostrar_lista && $cliente): ?>

        <h3>Editar Datos del Cliente</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="hidden" name="id_clientes" value="<?php echo $cliente['id_clientes']; ?>">

            <label>Nombre:</label><br>
            <input type="text" name="nombres" value="<?php echo htmlspecialchars($cliente['nombres']); ?>" required><br><br>

            <label>Apellido Paterno:</label><br>
            <input type="text" name="apellido_paterno" value="<?php echo htmlspecialchars($cliente['apellido_paterno']); ?>" required><br><br>

            <label>Apellido Materno:</label><br>
            <input type="text" name="apellido_materno" value="<?php echo htmlspecialchars($cliente['apellido_materno']); ?>" required><br><br>

            <label>CI:</label><br>
            <input type="number" name="ci" value="<?php echo htmlspecialchars($cliente['ci']); ?>" required><br><br>
            
            <label>Dirección:</label><br>
            <input type="text" name="direccion" value="<?php echo htmlspecialchars($cliente['direccion']); ?>" required><br><br>
            
            <input type="submit" name="guardar" value="Guardar Cambios">
            <a href="ModificarClientes.php"><button type="button">Cancelar</button></a>
        </form>

    <?php else: ?>

        <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="text" name="buscar" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar por nombre o apellido">
            <input type="submit" value="Buscar">
            <a href="ModificarClientes.php"><button type="button">Ver Todos</button></a>
        </form>
        <br>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table border="1">
            <tr>
                <th>Sel</th>   
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>CI</th>
                <th>Dirección</th>
            </tr>
            <?php
            if (count($resultados) > 0) {
                foreach ($resultados as $columna) {
                    echo "<tr>";
                    echo "<td><input type=\"checkbox\" name=\"ids[]\" value=\"" . $columna['id_clientes'] . "\"></td>";
                    echo "<td>" . $columna['id_clientes'] . "</td>";
                    echo "<td>" . $columna['nombres'] . "</td>";
                    echo "<td>" . $columna['apellido_paterno'] . "</td>";
                    echo "<td>" . $columna['apellido_materno'] . "</td>";
                    echo "<td>" . $columna['ci'] . "</td>";
                    echo "<td>" . $columna['direccion'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No se encontraron registros.</td></tr>";
            }
            ?>
        </table>
        <br>
        <input type="submit" name="modificar" value="Modificar Seleccionado" onclick="return confirm('¿Está seguro de editar este registro?');">
        </form>
    <?php endif; ?>

    <br>
    <a href="MostrarClientes.php">Volver a Lista de Clientes</a> | <a href="AdicionarClientes.php">Adicionar Cliente</a> | <a href="EliminarClientes.php">Eliminar Cliente</a>
</body>
</html>
        
<?php
// Cerrar conexión PDO
$conn = null;
?>