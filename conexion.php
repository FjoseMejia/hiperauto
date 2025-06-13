<?php

function Conectar(){
	$servername 	= "localhost";
	$db 			= "hiperautov2";
	$username 		= "root";
	$password 		= "root";
	$conexion = mysqli_connect($servername, $username, $password, $db);
	
	if (!$conexion){
		die("Error de Conexion: " . mysqli_connect_error());	}
	else{  
		return $conexion;										
	} 
}


