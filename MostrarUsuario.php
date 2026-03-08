<?php
session_start();
require_once 'conexion.php';

// Verificación de seguridad
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$mensaje = "";
$usuario_edit = null;

// 1. BUSCAR el usuario si se recibe un ID por la URL (desde el listado)
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        $usuario_edit = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $mensaje = "<div class='error-msg'>Error al buscar: " . $e->getMessage() . "</div>";
    }
}

// 2. ACTUALIZAR los datos cuando se presiona "Guardar Cambios"
if (isset($_POST['actualizar'])) {
    $id = intval($_POST['id_usuario']);
    $nombre = trim($_POST['nombre_usuario']);
    $pass = $_POST['contraseña'];
    $email = trim($_POST['e_mail']);
    $tipo = $_POST['tipo_usuario'];

    if (!empty($nombre) && !empty($pass)) {
        try {
            $sql = "UPDATE usuarios SET nombre_usuario = ?, contraseña = ?, e_mail = ?, tipo_usuario = ? WHERE id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre, $pass, $email, $tipo, $id]);
            $mensaje = "<div class='success-msg'>¡Usuario actualizado correctamente!</div>";
            
            // Refrescar los datos para el formulario
            $usuario_edit = ['id_usuario'=>$id, 'nombre_usuario'=>$nombre, 'contraseña'=>$pass, 'e_mail'=>$email, 'tipo_usuario'=>$tipo];
        } catch (PDOException $e) {
            $mensaje = "<div class='error-msg'>Error al actualizar: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensaje = "<div class='error-msg'>Nombre y Contraseña son obligatorios.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Usuario - UDES</title>
    <link rel="stylesheet" href="misestilos.css">
    <style>
        .success-msg { color: #27ae60; background: #e8f5e9; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 20px; border: 1px solid #27ae60; }
        .error-msg { color: #c0392b; background: #f9ebea; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 20px; border: 1px solid #c0392b; }
        .form-container { max-width: 600px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2><i class="fas fa-user-edit"></i> Modificar Usuario</h2>
        
        <?php echo $mensaje; ?>

        <?php if ($usuario_edit): ?>
            <form method="POST" action="ModificarUsuario.php">
                <input type="hidden" name="id_usuario" value="<?php echo $usuario_edit['id_usuario']; ?>">

                <label>ID Usuario (No editable):</label>
                <input type="text" value="<?php echo $usuario_edit['id_usuario']; ?>" disabled>

                <label>Nombre de Usuario:</label>
                <input type="text" name="nombre_usuario" value="<?php echo htmlspecialchars($usuario_edit['nombre_usuario']); ?>" required>

                <label>Contraseña:</label>
                <input type="text" name="contraseña" value="<?php echo htmlspecialchars($usuario_edit['contraseña']); ?>" required>

                <label>E-Mail:</label>
                <input type="email" name="e_mail" value="<?php echo htmlspecialchars($usuario_edit['e_mail']); ?>">

                <label>Tipo de Usuario:</label>
                <select name="tipo_usuario" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #ccc;">
                    <option value="Estudiante" <?php if($usuario_edit['tipo_usuario'] == 'Estudiante') echo 'selected'; ?>>Estudiante</option>
                    <option value="Docente" <?php if($usuario_edit['tipo_usuario'] == 'Docente') echo 'selected'; ?>>Docente</option>
                    <option value="Administrador" <?php if($usuario_edit['tipo_usuario'] == 'Administrador') echo 'selected'; ?>>Administrador</option>
                </select>

                <input type="submit" name="actualizar" value="Guardar Cambios">
            </form>
        <?php else: ?>
            <div class="error-msg">Por favor, selecciona un usuario desde la lista para modificar.</div>
        <?php endif; ?>

        <div class="enlace-container">
            <a href="MostrarUsuario.php">Volver al Listado</a> | <a href="index.php">Ir al Inicio</a>
        </div>
    </div>
</body>
</html>