<?php
include "../conexion.php";
$link= conectar();
date_default_timezone_set("America/Bogota");
$query= "SELECT expiracion  FROM tokens_recuperacion WHERE id= 1";
$resultExpirate= mysqli_query($link, $query);

$dateExpirate= mysqli_fetch_array($resultExpirate);
var_dump($dateExpirate);
echo "Hola ".$dateExpirate["expiracion"]."<br>";


$dataAfter= strtotime("2025-06-12 08:04:00");
var_dump($dataAfter);
$dataNow= strtotime(date('Y-m-d H:i:s'));
echo date('Y-m-d H:i:s')."funcion date <br>";
echo $dateX= time()."funcion time <br>";
$dateX= new DateTime();
echo $dateX->format("d-m-y")."s <br>";
if($dataAfter< $dataNow){
	echo "Soy la menor: ".date('Y-m-d H:i:s', $dataAfter);;
}

$fecha= "2025-06-12 08:04:00";
$fecha= "2025-06-13 08:04:00";
