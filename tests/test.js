let nombre= "monitor 20 pulgadas";
let precio= 300;
let disponible= true;

let producto= {
	nombre: nombre,
	precio: precio,
	disponible: disponible
}

console.log(producto);

if($insertUser){
					$result_table["state"]= 1;
					$result_table["message"]= "Registro exitoso";
				}else{
					$result_table["state"]= 0;
					$result_table["message"]= "Error ".mysqli_error($link);
				}