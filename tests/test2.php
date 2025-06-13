<?php
require_once "test.php";
include "../conexion.php";
$link= conectar();

$prueba= new GetQuery("tipo_documento");
$resultDocument= $prueba->search($link);

$type_document= mysqli_fetch_array($resultDocument);
var_dump($type_document);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!--Css Bootstrap  -->
    
</head>
<body>
    <form action="test.php" method="POST">
		<input type="text" name="email">
		<input type="password" name="password">
		<button type="input">Ingresar</button>
	</form>

    
</body>
</html>