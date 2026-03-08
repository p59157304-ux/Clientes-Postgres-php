<?php
require_once 'conexionpg.php';

$mostrar_lista=TRUE;
$mensaje="";

// Logica de Actualizacion
if (isset($_POST['guardar'])) {
    $id = $_POST['id_clientes'];
    $nombres = $_POST['nombres'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $ci = $_POST['ci'];
    $direccion = $_POST['direccion'];

    //consulta SQL para actualizar
    $sql_update = "UPDATE clientes SET nombres= '$nombres', apellido_paterno='$apellido_paterno', apellido_materno='$apellido_materno', ci='$ci', direccion='$direccion' WHERE id_clientes='$id'";

    if (pg_query($conectar, $sql_update)) {
        $mensaje = "Cliente actualizado correctamente.";
    } else {
        $mensaje = "Error al actualizar: " . pg_last_error($conectar);
    }
}

// Logica para mostrar el formulario de edicion
if (isset($_POST['modificar'])) {
    if(isset($_POST['ids']) && count($_POST['ids']) == 1) {
        $id_editar = $_POST['ids'][0];

        $sql_buscar = "SELECT * FROM clientes WHERE id_clientes='$id_editar'";
        $resultado = pg_query($conectar, $sql_buscar);

        if ($cliente = pg_fetch_array($resultado)) {
            $mostrar_lista = false; //ocultar lista para mostrar el formulario
        } else {
            $mensaje = "Error al recuperar los datos del cliente.";
        }
    } else {
        $mensaje = "Por favor, seleccione exactamente un cliente para modificar.";
    }       
}

// logica del Buscador y Listado
$where = "";
$busqueda = "";
if (isset($_GET['buscar'])) {
    $busqueda = $_GET['buscar'];
    $busqueda_segura = pg_escape_string($conectar, $busqueda);
    $where = "WHERE nombres LIKE '%$busqueda_segura%' OR apellido_paterno LIKE '%$busqueda_segura%' OR apellido_materno LIKE '%$busqueda_segura%'";
}

$sql_lista = "SELECT * FROM clientes $where";
$consulta = pg_query($conectar, $sql_lista);
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
        <p style="color:blue; font-weight: bold;"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <?php if (!$mostrar_lista && isset($cliente)): ?>

        <h3>Editar Datos del Cliente</h3>
        <form method="post" action="ModificarClientes.php">
            <input type="hidden" name="id_clientes" value="<?php echo $cliente['id_clientes']; ?>">

            <label>Nombres</label><br>
            <input type="text" name="nombres" value="<?php echo $cliente['nombres']; ?>" required><br><br>

            <label>Apellido Paterno</label><br>
            <input type="text" name="apellido_paterno" value="<?php echo $cliente['apellido_paterno']; ?>" required><br><br>

            <label>Apellido Materno</label><br>
            <input type="text" name="apellido_materno" value="<?php echo $cliente['apellido_materno']; ?>" required><br><br>

            <label>CI</label><br>
            <input type="number" name="ci" value="<?php echo $cliente['ci']; ?>" required><br><br>
            
            <label>Direccion</label><br>
            <input type="text" name="direccion" value="<?php echo $cliente['direccion']; ?>" required><br><br>
            
            
            <input type="submit" name="guardar" value="Guardar Cambios">
            <a href="ModificarClientes.php"><button type="button">Cancelar</button></a>
        </form>

    <?php else: ?>

        <form method="get" action="ModificarClientes.php">
            <input type="text" name="buscar" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar por nombre o apellido">
            <input type="submit" values="Buscar">
            <a href="ModificarClientes.php"><button type="button">Ver Todos</button></a>
        </form>
        <br>

        <form method="post" action="ModificarClientes.php">
        <table border="1">
            <tr>
            <th>Sel</th>   
            <th>ID</th>
            <th>Nombres</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>CI</th>
            <th>Dirección</th>
            </tr>
            <?php
            if (pg_num_rows($consulta) > 0) {
                while ($columna = pg_fetch_array($consulta)) {
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
                echo "<tr><td colspan='9'>No se encontraron registros.</td></tr>";
            }
            ?>
        </table>
        <br>
        <input type="submit" name="modificar" value="Modificar Seleccionado">
        </form>
    <?php endif; ?>

    <br>
    <a href="MostarClientes.php">Volver a Lista de Clientes</a> | <a href="AdicionarClientes.php">Adicionar</a> | <a href="EliminarClientes.php">Eliminar</a>
</body>
</html>
       
<?php
pg_close($conectar);
?>