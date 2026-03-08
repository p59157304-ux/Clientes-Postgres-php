<?php
/**
 * Eliminar y Buscar Clientes en SQL Server
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

$mensaje = "";

// 2. Lógica para eliminar registros seleccionados
if (isset($_POST['eliminar']) && isset($_POST['id'])) {
    $ids_a_eliminar = $_POST['id'];
   
    if (!empty($ids_a_eliminar)) {
        // Sanitizar IDs: asegurar que sean números
        $ids_seguros = array_map('intval', $ids_a_eliminar);
        $lista_ids = implode(',', $ids_seguros);

        // SQL Server query para eliminar por IN(...)
        $sql_delete = "DELETE FROM clientes WHERE id_clientes IN ($lista_ids)";

        try {
            $conn->exec($sql_delete);
            $mensaje = "<p style='color: green;'>Registros eliminados correctamente.</p>";
        } catch (PDOException $e) {
            $mensaje = "<p style='color: red;'>Error al eliminar: " . $e->getMessage() . "</p>";
        }
    }
}

// 3. Lógica para el buscador (CORREGIDA)
$where = "";
$busqueda = "";
$parametros = array();

if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $busqueda = $_GET['buscar'];
    // Usamos LIKE con parámetros de forma más segura para SQL Server
    $where = "WHERE nombres LIKE ? 
              OR apellido_paterno LIKE ? 
              OR apellido_materno LIKE ?";
              
    // Preparamos los parámetros con los comodines % aquí
    $param_valor = "%$busqueda%";
    $parametros = array($param_valor, $param_valor, $param_valor);
}

// 4. Consulta para obtener los clientes
$sql = "SELECT * FROM clientes $where ORDER BY id_clientes ASC";
$stmt = $conn->prepare($sql);
$stmt->execute($parametros);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Clientes</title>
    <link rel="stylesheet" href="misestilos.css">
    <script>
        function seleccionarTodos(source) {
            checkboxes = document.getElementsByName('id[]');
            for(var i=0, n=checkboxes.length; i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
</head>
<body>
    <h2>Eliminar Clientes</h2>

    <?php echo $mensaje; // Mostrar mensajes de éxito o error ?>

    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="text" name="buscar" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar por nombre o apellido">
        <input type="submit" value="Buscar">
        <a href="EliminarClientes.php"><button type="button">Ver Todos</button></a>
    </form>
    <br>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table>
            <tr>
                <th><input type="checkbox" onClick="seleccionarTodos(this)"></th>
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
                    echo "<td><input type=\"checkbox\" name=\"id[]\" value=\"" . $columna['id_clientes'] . "\"></td>";
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
        <input type="submit" name="eliminar" value="Eliminar Seleccionados" onclick="return confirm('¿Está seguro de que desea eliminar los registros seleccionados?');">
    </form>
    <br>
    <a href="MostrarClientes.php">Volver a Lista de Clientes</a> | <a href="AdicionarClientes.php">Adicionar Nuevo Cliente</a>
</body>
</html>

<?php
// Cerrar conexión
$conn = null;
?>