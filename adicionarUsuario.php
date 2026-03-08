<?php
require_once 'conexion.php'; // Tu archivo que usa PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contraseña = $_POST['contraseña'];
    $e_mail = $_POST['e_mail'];
    $tipo_usuario = $_POST['tipo_usuario']; // Ahora recibirá lo que el usuario escriba

    try {
        // Usamos sentencias preparadas de PDO para evitar inyección SQL
        $sql = "INSERT INTO usuarios (nombre_usuario, contraseña, e_mail, tipo_usuario) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([$nombre_usuario, $contraseña, $e_mail, $tipo_usuario])) {
            echo "<p style='color: green; font-weight: bold;'>¡Usuario registrado con éxito en SQL Server!</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error al insertar: " . $e->getMessage() . "</p>";
    }
}
$conn = null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Usuario</title>
    <link rel="stylesheet" href="misestilos.css">
</head>
<body>
    <h2>Registrar Nuevo Usuario</h2>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        
        <label for="nombre_usuario">Nombre de Usuario:</label><br>
        <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Ej: marcos_paz" required><br><br>

        <label for="contraseña">Contraseña:</label><br>
        <input type="password" id="contraseña" name="contraseña" required><br><br>

        <label for="e_mail">Correo Electrónico:</label><br>
        <input type="email" id="e_mail" name="e_mail" placeholder="usuario@udes.edu.bo" required><br><br>

        <label for="tipo_usuario">Tipo de Usuario:</label><br>
        <input type="text" id="tipo_usuario" name="tipo_usuario" placeholder="Ej: Estudiante o Docente" required><br><br>

        <input type="submit" value="Guardar Usuario">
    </form>

    <br>
    <a href="MostrarUsuario.php" style="text-decoration: none; color: #0078d4;">Volver al listado</a>
</body>
</html>