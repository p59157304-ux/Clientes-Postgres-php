<?php
require_once 'conexion.php'; 

$mensaje = "";

// 1. Lógica para eliminar (Se mantiene igual)
if (isset($_POST['eliminar']) && isset($_POST['id'])) {
    $ids_a_eliminar = $_POST['id'];
    if (!empty($ids_a_eliminar)) {
        try {
            $ids_seguros = array_map('intval', $ids_a_eliminar);
            $lista_ids = implode(',', $ids_seguros);
            $sql_delete = "DELETE FROM usuarios WHERE id_usuario IN ($lista_ids)";
            $conn->query($sql_delete);
            $mensaje = "<p style='color: green;'>Registros eliminados correctamente.</p>";
        } catch (PDOException $e) {
            $mensaje = "<p style='color: red;'>Error al eliminar: " . $e->getMessage() . "</p>";
        }
    }
}

// 2. Lógica para el buscador
$where = "";
$busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : "";
if ($busqueda !== "") {
    $term = $conn->quote('%' . $busqueda . '%');
    $where = "WHERE nombre_usuario LIKE $term OR e_mail LIKE $term OR tipo_usuario LIKE $term";
}

// 3. Obtención de datos
try {
    $sql = "SELECT * FROM usuarios $where ORDER BY id_usuario ASC";
    $stmt = $conn->query($sql);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Usuarios</title>
    <link rel="stylesheet" href="misestilos.css">
    <script>
        function seleccionarTodos(source) {
            var checkboxes = document.getElementsByName('id[]');
            for(var i=0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
</head>
<body>
    <div class="contenedor">
        <h2>Eliminar Usuarios</h2>
        
        <?php echo $mensaje; ?>

        <form method="get" action="EliminarUsuario.php" class="buscador">
            <input type="text" name="buscar" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar...">
            <input type="submit" value="Buscar">
            <a href="EliminarUsuario.php"><button type="button">Ver Todos</button></a>
        </form>

        <form method="post" action="EliminarUsuario.php">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" onClick="seleccionarTodos(this)"></th>
                        <th>ID</th>
                        <th>Nombre Usuario</th>
                        <th>Contraseña</th>
                        <th>E-Mail</th>
                        <th>Tipo Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($resultados) > 0): ?>
                        <?php foreach ($resultados as $columna): ?>
                            <tr>
                                <td><input type="checkbox" name="id[]" value="<?php echo $columna['id_usuario']; ?>"></td>
                                <td><?php echo $columna['id_usuario']; ?></td>
                                <td><?php echo $columna['nombre_usuario']; ?></td>
                                <td><?php echo $columna['contraseña']; ?></td>
                                <td><?php echo $columna['e_mail']; ?></td>
                                <td><?php echo $columna['tipo_usuario']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center;">No se encontraron registros.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <br>
            <input type="submit" name="eliminar" value="Eliminar Seleccionados" class="btn-eliminar" onclick="return confirm('¿Seguro?');">
        </form>
        <br>
        <div class="enlaces">
            <a href="MostrarUsuario.php">Volver</a> | <a href="AdicionarUsuario.php">Nuevo</a>
        </div>
    </div>
</body>
</html>