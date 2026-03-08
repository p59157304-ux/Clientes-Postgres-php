<?php
$servidor = "localhost";
$baseDatos = "ProyClientes";
$puerto = "5432";
$usuario = "postgres";
$contraseña = "12345";

$conexionStr = "host=$servidor port=$puerto dbname=$baseDatos user=$usuario password=$contraseña";
$conectar = pg_connect($conexionStr) or die("Error en la conexión: " . pg_last_error());

echo "Se conectó a la BD correctamente <br>";

// Consulta corregida según la tabla 'clientes' de la imagen
$query = "SELECT id_clientes, nombres, apellido_paterno, apellido_materno, ci, direccion FROM public.clientes ORDER BY id_clientes ASC";
$resultado = pg_query($conectar, $query);

if (!$resultado) {
    echo "Error en la consulta: " . pg_last_error();
    exit;
}
?>