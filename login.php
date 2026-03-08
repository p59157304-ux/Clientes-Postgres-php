<?php
session_start();

// 1. Conexión silenciosa
require_once 'conexion.php'; 

$error = "";

// Procesar cierre de sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Redirigir si ya hay sesión activa
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

// 2. Lógica de Acceso con PDO
if (isset($_POST['acceder'])) {
    $nombre_usuario = isset($_POST['nombre_usuario']) ? trim($_POST['nombre_usuario']) : '';
    $contraseña = isset($_POST['contraseña']) ? $_POST['contraseña'] : '';

    if (empty($nombre_usuario) || empty($contraseña)) {
        $error = "Por favor, complete todos los campos.";
    } else {
        try {
            // Consulta preparada para udesdb
            $sql = "SELECT id_usuario, nombre_usuario, contraseña, tipo_usuario FROM usuarios WHERE nombre_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre_usuario]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && $contraseña === $usuario['contraseña']) {
                // Iniciar sesión
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
                $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

                header("Location: index.php");
                exit;
            } else {
                $error = "Nombre de usuario o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            $error = "Error en el sistema: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema - UDES</title>
    <link rel="stylesheet" href="misestilos.css">
    <style>
        /* Estilos específicos para el layout del Login */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .contenedor_login {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .error-box {
            color: #d32f2f;
            background-color: #ffebee;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            border-left: 4px solid #d32f2f;
        }
    </style>
</head>
<body>

    <div class="contenedor_login">
        <h1 style="text-align: center; color: #333; margin-bottom: 30px;">Acceso al Sistema</h1>

        <?php if($error): ?>
            <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="hidden" name="acceder" value="1">

            <div style="margin-bottom: 20px;">
                <label style="display:block; font-weight:bold; margin-bottom:8px;">Nombre de usuario:</label>
                <input type="text" name="nombre_usuario" placeholder="Ingrese su usuario" required 
                       style="width:100%; padding:12px; border:1px solid #ddd; border-radius:5px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display:block; font-weight:bold; margin-bottom:8px;">Contraseña:</label>
                <input type="password" name="contraseña" placeholder="Ingrese su contraseña" required 
                       style="width:100%; padding:12px; border:1px solid #ddd; border-radius:5px;">
            </div>

            <input type="submit" value="Acceder" style="background: #667eea; color:white; cursor:pointer;">
        </form>

        <div style="text-align: center; margin-top: 30px; font-size: 12px; color: #999; border-top: 1px solid #eee; padding-top: 20px;">
            <p>Sistema de Gestión Educativa UDES</p>
            <p>© 2026 - El Alto, Bolivia</p>
        </div>
    </div>

</body>
</html>