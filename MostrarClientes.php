<?php
require_once 'conexionpg.php';
//CONSULTA
$query="SELECT id_clientes, nombres, apellido_paterno, apellido_materno, ci, direccion FROM clientes ORDER BY id_clientes ASC";

$resultado=pg_query($conectar, $query);
if (!$resultado){
    die(" error en la consulta");
    
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>listado de clientes</title>
    <link rel="stylesheet" href="misestilos.css">

</head>
<body>
    <h2>Listado de clientes</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nombres</th>         
        <th>Apellido_paterno</th>
        <th>Apellido_materno</th>
        <th>CI</th>        
        <th>Direccion</th> </tr> 
         
<?php
while($fila=pg_fetch_assoc($resultado)){
    echo "<tr>";
    echo "<td>".$fila['id_clientes']."</td>";
    echo "<td>".$fila['nombres']."</td>";
    echo "<td>".$fila['apellido_paterno']."</td>";
    echo "<td>".$fila['apellido_materno']."</td>";
    echo "<td>".$fila['ci']."</td>";
    echo "<td>".$fila['direccion']."</td>";
    echo "</tr>";

}?> 
</table> 
</body> 
</html>

// cerrar la conexion
<?php
pg_close($conectar);
?>