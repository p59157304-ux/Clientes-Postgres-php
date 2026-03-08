<?php
require_once 'conexion.php'; 

$mostrar_lista = true;
$mensaje = "";

// 1. Lógica de Actualización (UPDATE)
if (isset($_POST['guardar'])) {
    $id = $_POST['id_usuario'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $contraseña = $_POST['contraseña'];
    $e_mail = $_POST['e_mail'];
    $tipo_usuario = $_POST['tipo_usuario'];

    try {
        $sql_update = "UPDATE usuarios SET nombre_usuario = ?, contraseña = ?, e_mail = ?, tipo_usuario = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql_update);
        if ($stmt->execute([$nombre_usuario, $contraseña, $e_mail, $tipo_usuario, $id])) {
            $mensaje = "Usuario actualizado correctamente.";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al actualizar: " . $e->getMessage();
    }
}

// 2. Lógica para mostrar el formulario de edición (SELECT de uno solo)
if (isset($_POST['modificar'])) {
    if (isset($_POST['ids']) && count($_POST['ids']) == 1) {
        $id_editar = $_POST['ids'][0];

        try {
            $sql_buscar = "SELECT * FROM usuarios WHERE id_usuario = ?";
            $stmt = $conn->prepare($sql_buscar);
            $stmt->execute([$id_editar]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                $mostrar_lista = false; // Ocultamos la tabla
            } else {
                $mensaje = "Error al recuperar los datos del usuario.";
            }
        } catch (PDOException $e) {
            $mensaje = "Error: " . $e->getMessage();
        }
    } else {
        $mensaje = "Por favor, seleccione exactamente un usuario para modificar.";
    }       
}

// 3. Lógica del Buscador y Listado (SELECT general)
$where = "";
$busqueda = "";
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $busqueda = $_GET['buscar'];
    $term = $conn->quote('%' . $busqueda . '%');
    $where = "WHERE nombre_usuario LIKE $term OR e_mail LIKE $term OR tipo_usuario LIKE $term";
}

try {
    $sql_lista = "SELECT * FROM usuarios $where ORDER BY id_usuario ASC";
    $consulta = $conn->query($sql_lista);
    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al cargar la lista: " . $e->getMessage();
}
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
            <input type="text" name="nombre_usuario" value="<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>" required><br><br>

            <label>Contraseña</label><br>
            <input type="text" name="contraseña" value="<?php echo htmlspecialchars($usuario['contraseña']); ?>" required><br><br>

            <label>E-Mail</label><br>
            <input type="email" name="e_mail" value="<?php echo htmlspecialchars($usuario['e_mail']); ?>" required><br><br>

            <label>Tipo Usuario</label><br>
            <input type="text" name="tipo_usuario" value="<?php echo htmlspecialchars($usuario['tipo_usuario']); ?>" required><br><br>

            <input type="submit" name="guardar" value="Guardar Cambios">
            <a href="ModificarUsuario.php"><button type="button">Cancelar</button></a>
        </form>

    <?php else: ?>
        <form method="get" action="ModificarUsuario.php">
            <input type="text" name="buscar" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar por nombre o tipo">
            <input type="submit" value="Buscar">
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
                    <th>E-Mail</th>
                    <th>Tipo Usuario</th>
                </tr>
                <?php if (count($resultados) > 0): ?>
                    <?php foreach ($resultados as $columna): ?>
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="<?php echo $columna['id_usuario']; ?>"></td>
                            <td><?php echo $columna['id_usuario']; ?></td>
                            <td><?php echo $columna['nombre_usuario']; ?></td>
                            <td><?php echo $columna['contraseña']; ?></td>
                            <td><?php echo $columna['e_mail']; ?></td>
                            <td><?php echo $columna['tipo_usuario']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No se encontraron registros.</td></tr>
                <?php endif; ?>
            </table>
            <br>
            <input type="submit" name="modificar" value="Modificar Seleccionado">
        </form>
    <?php endif; ?>

    <br>
    <a href="MostrarUsuario.php">Volver a lista</a> | <a href="AdicionarUsuario.php">Adicionar</a> | <a href="EliminarUsuario.php">Eliminar</a>
</body>
</html>
<?php $conn = null; ?>