<?php
require_once 'conexionpg.php';

$mostrar_lista=TRUE;
$mensaje="";

// Logica de Actualizacion
if (isset($_POST['guardar'])) {
    $id = $_POST['id_usuario'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $contraseña = $_POST['contraseña'];
    $e_mail = $_POST['e_mail'];
    $tipo_usuario = $_POST['tipo_usuario'];

    //consulta SQL para actualizar
    $sql_update = "UPDATE usuarios SET nombre_usuario= '$nombre_usuario', contraseña='$contraseña', e_mail='$e_mail', tipo_usuario='$tipo_usuario' WHERE id_usuario='$id'";

    if (pg_query($conectar, $sql_update)) {
        $mensaje = "Usuario actualizado correctamente.";
    } else {
        $mensaje = "Error al actualizar: " . pg_last_error($conectar);
    }
}

// Logica para mostrar el formulario de edicion
if (isset($_POST['modificar'])) {
    if(isset($_POST['ids']) && count($_POST['ids']) == 1) {
        $id_editar = $_POST['ids'][0];

        $sql_buscar = "SELECT * FROM usuarios WHERE id_usuario='$id_editar'";
        $resultado = pg_query($conectar, $sql_buscar);

        if ($usuario = pg_fetch_array($resultado)) {
            $mostrar_lista = false; //ocultar lista para mostrar el formulario
        } else {
            $mensaje = "Error al recuperar los datos del usuario.";
        }
    } else {
        $mensaje = "Por favor, seleccione exactamente un usuario para modificar.";
    }       
}

// logica del Buscador y Listado
$where = "";
$busqueda = "";
if (isset($_GET['buscar'])) {
    $busqueda = $_GET['buscar'];
    $busqueda_segura = pg_escape_string($conectar, $busqueda);
    $where = "WHERE nombre_usuario LIKE '%$busqueda_segura%' OR e_mail LIKE '%$busqueda_segura%' OR tipo_usuario LIKE '%$busqueda_segura%'";
}

$sql_lista = "SELECT * FROM usuarios $where";
$consulta = pg_query($conectar, $sql_lista);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Usuario</title>
    <link rel="stylesheet" href="misestilos.css">
</head>
<body>
    <h2>Modificar Usuario</h2>

    <?php if ($mensaje): ?>
        <p style="color:blue; font-weight: bold;"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <?php if (!$mostrar_lista && isset($usuario)): ?>

        <h3>Editar Datos del Usuario</h3>
        <form method="post" action="ModificarUsuario.php">
            <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">

            <label>Nombre Usuario</label><br>
            <input type="text" name="nombre_usuario" value="<?php echo $usuario['nombre_usuario']; ?>" required><br><br>

            <label>Contraseña</label><br>
            <input type="text" name="contraseña" value="<?php echo $usuario['contraseña']; ?>" required><br><br>

            <label>E Mail</label><br>
            <input type="text" name="e_mail" value="<?php echo $usuario['e_mail']; ?>" required><br><br>

            
            <label>Tipo Usuario</label><br>
            <select id="tipo_usuario" name="tipo_usuario">
                <option value="0" <?php if($usuario ['tipo_usuario']==0) echo 'selected'?>> Seleccione una opcion </option> 
                <option value="1" <?php if($usuario ['tipo_usuario']==1) echo 'selected'?>> Administrador </option>
                <option value="2" <?php if($usuario ['tipo_usuario']==2) echo 'selected'?>> Lector </option>
                <option value="3" <?php if($usuario ['tipo_usuario']==3) echo 'selected'?>> Editor </option>
                </select>
                <input type="submit" name="guardar" value="Guardar Cambios">
            <a href="ModificarUsuario.php"><button type="button">Cancelar</button></a>
        </form>

    <?php else: ?>

        <form method="get" action="ModificarUsuario.php">
            <input type="text" name="buscar" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar por nombre o tipo de usuario">
            <input type="submit" values="Buscar">
            <a href="ModificarUsuario.php"><button type="button">Ver Todos</button></a>
        </form>
        <br>

        <form method="post" action="ModificarUsuario.php">
        <table border="1">
            <tr>
            <th>Sel</th>   
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
                    echo "<td><input type=\"checkbox\" name=\"ids[]\" value=\"" . $columna['id_usuario'] . "\"></td>";
                    echo "<td>" . $columna['id_usuario'] . "</td>";
                    echo "<td>" . $columna['nombre_usuario'] . "</td>";
                    echo "<td>" . $columna['contraseña'] . "</td>";
                    echo "<td>" . $columna['e_mail'] . "</td>";
                    echo "<td>" . $columna['tipo_usuario'] . "</td>";
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
    <a href="MostrarUsuario.php">Volver a lista de usuarios</a> | <a href="AdicionarUsuario.php">Adicionar</a> | <a href="EliminarUsuario.php">Eliminar</a>
</body>
</html>
       
<?php
pg_close($conectar);
?>