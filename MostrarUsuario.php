<?php
require_once 'conexionpg.php';
//CONSULTA
$query="SELECT id_usuario, nombre_usuario, contraseña, e_mail, tipo_usuario FROM usuarios ORDER BY id_usuario ASC";

$resultado=pg_query($conectar, $query);
if (!$resultado){
    die(" error en la consulta");
    
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de usuarios</title>
    <link rel="stylesheet" href="misestilos.css">

</head>
<body>
    <h2>Listado de usuarios</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nombre_usuario</th>         
        <th>Contraseña</th>
        <th>E_mail</th>
        <th>Tipo_usuario</th>  </tr>        
        
         
<?php
while($fila=pg_fetch_assoc($resultado)){
    echo "<tr>";
    echo "<td>".$fila['id_usuario']."</td>";
    echo "<td>".$fila['nombre_usuario']."</td>";
    echo "<td>".$fila['contraseña']."</td>";
    echo "<td>".$fila['e_mail']."</td>";
    echo "<td>".$fila['tipo_usuario']."</td>";
    echo "</tr>";

}?> 
</table> 
</body> 
</html>

<?php
pg_close($conectar);
?>