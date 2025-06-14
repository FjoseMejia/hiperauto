<?php

require_once '../Model/process_forms.php';
require_once "../../conexion.php";
require_once "../../herramientas/key/llave.php";
require_once "../Model/send_email.php";
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
$link= conectar();

/*echo json_encode(['state'=> 0, 'message'=> 'Test OK']);
exit;
*/
$result_table= [
	"state"=> 0,
	"message"=> ""
];
function checkObjectOrExit($object, $message) {
    if (!$object) {
        echo json_encode(['state' => 0, 'message' => $message]);
        exit;
    }
}

function formatEmail($text){
	return strtolower($text); 
}

function verificateEmail($email) {
	$regex = '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/';
	if(!preg_match($regex, $email) === 1){
		$result_table["state"]= 0;
		$result_table["message"]= "Digite un correo valido Unu";
		echo json_encode($result_table);
		exit();
	}
	return true;
}

function encryptPassword($password){
	return password_hash($password, PASSWORD_BCRYPT, ["cost"=>11]);
}

if(isset($_REQUEST['type-form'])) {
	switch($_REQUEST["type-form"]){
		case "login":		
			$password= $_POST["password"];
			$_POST["email"]= formatEmail($_POST["email"]);
			
			verificateEmail($_POST["email"]);	
	
			$user= new ProcessLogin($_POST, $link);			
			$objectUser= $user->verificateUser();
			checkObjectOrExit($objectUser, "Error code 500! Consulte con el proveedor");
			
			$dataUser= mysqli_fetch_array($objectUser);
			if(password_verify($password, $dataUser["password"])){			
				$result_table['state']= 1;				
				session_start();
				$_SESSION['id']= $dataUser['id'];
				$_SESSION['state']= $result_table['state'];
			}else{
				$result_table['message']= "Credenciales Incorrectas";
			}
			echo json_encode($result_table);    
			break;
			
		case "register":			
			$encryptedPassword= encryptPassword($_POST["password"]);
			$_POST["password"]= $encryptedPassword;
			$_POST["email"]= formatEmail($_POST["email"]);
			if(! verificateEmail($_POST["email"])){
				$result_table[""]= 0;
				$result_table["message"]= "Digite un correo valido";
			}
			
			$newUser= new ProcessRegister($_POST, $link);
			$objectNewUser= $newUser->verificateData();
			checkObjectOrExit($objectNewUser, "Error code 500! Consulte con el proveedor");
			
			
			$dataUser= mysqli_fetch_array($objectNewUser);
			if(!$dataUser){
				$insertUser= $newUser-> saveUser();
				if($insertUser){
					$result_table["state"]= 1;
					$result_table["message"]= "Registro Existoso";
				}else{
					$result_table["message"]= "Error ".mysqli_error($link);
				}
			}else{
				$result_table["message"]= "El usuario ya existe";
			}	
			echo json_encode($result_table);
			break;
			
		case "recovery_password":		
			
			$_POST["email"]= formatEmail($_POST["email"]);
			verificateEmail($_POST['email']);
			$user= new ProcessRecovery($_POST, $link);
			$objectUser= $user->verificateUser();
			checkObjectOrExit($objectUser, "Error code 500! Consulte con el proveedor");
			
			if(mysqli_num_rows($objectUser) === 0){
				$result_table["state"]= 0;
				$result_table["message"]= "Correo no registrado en el sistema";
				echo json_encode($result_table);
				return;				
			}		
			
			if(verificateEmail($_POST['email'])){
				$dataUser= mysqli_fetch_array($objectUser); 
				$idUser= $dataUser['id'];				
				$expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));
				$token = bin2hex(random_bytes(32));
				$saveToken= $user->saveToken($idUser, $expiracion, $token);
				if(!$saveToken){
					$result_table["state"]= 0;
					$result_table["message"]= "Error 510 ".mysqli_error($link);
					echo json_encode($result_table);
					exit;
				}
				
				$result= sendEmail($email_remitente, $_POST['email'], $password, $token);
				if($result){
					$result_table["state"]= 1;
					$result_table["message"]= "Enlace de recuperación enviado";
				}else{
					$result_table["state"]= 0;
					$result_table["message"]= "No se pudo enviar el correo";
				}
			}else{
				$result_table["state"]= 0;
				$result_table["message"]= "Digite un correo valido";
			}
			
			echo json_encode($result_table);
			break;
		case "updatePassword":
		
			if(!($_POST["newPassword"]== $_POST["rePassword"])){
				$result_table["state"]= 1;
				$result_table["message"]= "Las contraseñas deben ser iguales";
				echo json_encode($result_table);
				return;
			}
			
			
			$newPasswordEncrypt= encryptPassword($_POST['newPassword']);
			$_POST['newPassword']= $newPasswordEncrypt;
			
		
			$updatePassword= new ProcessRecovery($_POST, $link);
			$dateNow= date('Y-m-d H:i:s');
			$resultVerificate= $updatePassword->verificateToken($dateNow);
			
			
			if($resultVerificate){				
				$resultUpdate= $updatePassword->updatePassword();
				if($resultUpdate){
					$result_table["state"]= 1;
					$result_table["message"]= "Contraseña actualizada correctamente";
				}else{
					$result_table["state"]= 1;
					$result_table["message"]= "Error al actualizar la contraseña";
				}
			}else{
				//RECORDAR ELIMINAR
				$result_table["state"]= 1;
				$result_table["message"]= "Su token ha expirado expirado";
			}
			
			echo json_encode($result_table);
			break;
		default:
			echo "Estamos en default";
	}
    
}