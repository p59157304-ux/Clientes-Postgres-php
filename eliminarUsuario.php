<?php
require_once 'conexionpg.php';
$mostrar_lista=true;
$mensaje="";

//Logica para eliminar registros seleccionados
if (isset($_POST['eliminar']) && isset($_POST['id'])) {
    $ids_a_eliminar = $_POST['id'];
    if (!empty($ids_a_eliminar)) {


        $ids_seguros = array_map('intval', $ids_a_eliminar);
        $lista_ids = implode(',', $ids_seguros);

        $sql_delete ="DELETE FROM usuarios WHERE id_usuario IN ($lista_ids)";

        if (pg_query($conectar, $sql_delete)) {
            echo "<p>Registros eliminados correctamente.</p>";
        } else {
            echo "Error al eliminar: " . pg_last_error($conectar);
        }
    }
}

// Logica para el buscador
$where = "";
$busqueda = "";
if (isset($_GET['buscar'])) {
    $busqueda = $_GET['buscar'];
    //Escapar caracteres especiales para evitar inyeccion SQL
    $busqueda_segura = pg_escape_string($conectar, $busqueda);
    $where = "WHERE nombre_usuario LIKE '%$busqueda_segura%' OR e_mail LIKE '%$busqueda_segura%' OR tipo_usuario LIKE '%$busqueda_segura%'";
}

//Consulta para obtener los clientes
$sql = "SELECT * FROM usuarios $where";
$consulta = pg_query($conectar, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Usuarios</title>
<link rel="stylesheet" href="misestilos.css">

    <script>
        function seleccionarTodos(source) {
            checkboxes = document.getElementsByName('ids[]');
            for(var i=0, n=checkboxes.length; i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
</head>
<body>
    <h2>Eliminar Usuarios</h2>

    <form method="get" action="EliminarUsuario.php">
        <input type="text" name="buscar" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar por nombre o e_mail">
        <input type="submit" values="Buscar">
        <a href="EliminarUsuario.php"><button type="button">Ver Todos</button></a>
        </form>
        <br>

    <form method="post" action="EliminarUsuario.php">
        <table >
            <tr>
            <th><input type="checkbox" onClick="seleccionarTodos(this)"></th>
            <th>ID</th>
            <th>Nombre Usuario</th>
            <th>Contraseña</th>
            <th>E Mail</th>
            <th>Tipo Usuario</th>
            </tr>
            <?php
            if (pg_num_rows($consulta) > 0) {
                while ($columna = pg_fetch_array($consulta)) {
                    echo "<tr>";
                    echo "<td><input type=\"checkbox\" name=\"id[]\" value=\"" . $columna['id_usuario'] . "\"></td>";
                    echo "<td>" . $columna['id_usuario'] . "</td>";
                    echo "<td>" . $columna['nombre_usuario'] . "</td>";
                    echo "<td>" . $columna['contraseña'] . "</td>";
                    echo "<td>" . $columna['e_mail'] . "</td>";
                    echo "<td>" . $columna['tipo_usuario'] . "</td>";
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
    <a href="MostrarUsuario.php">Volver a lista de usuarios</a> | <a href="AdicionarUsuario.php">Adicionar Nuevo Usuario</a>
</body>
</html>

<?php
pg_close($conectar);
?>