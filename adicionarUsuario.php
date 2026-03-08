<?php
require_once 'conexionpg.php';

//Verificar si se ha enviado el formulario
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $nombre_usuario = $_POST['nombre_usuario'];
    $contraseña = $_POST['contraseña'];
    $e_mail = $_POST['e_mail'];
    $tipo_usuario = $_POST['tipo_usuario'];
    
   

    //Consulta SQL para insertar datos
    $sql = "INSERT INTO usuarios (nombre_usuario, contraseña, e_mail, tipo_usuario) VALUES('$nombre_usuario','$contraseña', '$e_mail','$tipo_usuario')" ;

    //Ejecutar la consulta
    if (pg_query($conectar, $sql)) {
        echo "<p>Usuario agregado correctamente.</p>";
    } else {
        echo "Error: " . $sql . "<br>" . pg_last_error($conectar);
    }
}

pg_close($conectar);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Usuario</title>
    <link rel="stylesheet" href="misestilos.css">

</head>
<body>
    <h2>Adicionar Nuevo Usuario</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="nombre_usuario">Nombre Usuario:</label><br>
        <input type="text" id="nombre_usuario" name="nombre_usuario" required><br><br>

        <label for="contraseña">Contraseña:</label><br>
        <input type="text" id="contraseña" name="contraseña" required><br><br>

        <label for="e_mail">E Mail:</label><br>
        <input type="text" id="e_mail" name="e_mail" required><br><br>

        <label for="tipo_usuario">Tipo Usuario:</label><br>
        <select id="tipo_usuario" name="tipo_usuario">
            <option value="0" selected=""> seleccione una opcion </option> 
            <option value="1" > Administrador </option>
            <option value="2" > Lector </option>
            <option value="3" > Editor </option>

        <input type="submit" values="Adicionar Usuario">
    </form>
    <br>
    <a href="MostrarUsuario.php">Ver lista de Usuarios</a>
</body>
</html>