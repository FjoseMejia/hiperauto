<?php
include_once('../../herramientas/key/llave.php');
//header('Content-Type: text/html; charset='.$charset);
header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

require '../../herramientas/PHPMailer/src/Exception.php';
require '../../herramientas/PHPMailer/src/PHPMailer.php';
require '../../herramientas/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($email_remitente, $email_destinatario, $password, $token, $typeEmail= "recoveryPassword") {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = $email_remitente; // Tu dirección de correo de Gmail
        $mail->Password = $password; // Tu contraseña de Gmail
        $mail->SMTPSecure = 'tls'; // Activa la encriptación TLS
        $mail->Port = 587; // Puerto TCP para TLS

        // Remitente y destinatario
        $mail->setFrom($email_remitente, 'HiperAuto');
        $mail->addAddress($email_destinatario);
        
        if(($typeEmail== "register")){
                $mail->isHTML(true);
                $link = "localhost/hiperAuto/app/View/recovery_password.php?email=$email_destinatario&token=".urlencode($token);
                $mail->Subject= 'Restablecimiento de contraseña';
                $mail->Body = "
                        <h2></h2>
                        <p>Hola, hemos recibido una solicitud para restablecer tu clave.</p>
                        <p>Haz clic en el siguiente enlace para continuar con el proceso:</p>
                        <p><a href='$link'>$link</a></p>
                        <p>Si no solicitaste esto, puedes ignorar este correo.</p>
                ";
        }else{
                // Contenido del correo
                $mail->isHTML(true);
                $link = "localhost/hiperAuto/app/View/recovery_password.php?email=$email_destinatario&token=".urlencode($token);
                $mail->Subject= 'Restablecimiento de contraseña';
                $mail->Body = "
                    <h2></h2>
                    <p>Hola, hemos recibido una solicitud para restablecer tu clave.</p>
                    <p>Haz clic en el siguiente enlace para continuar con el proceso:</p>
                    <p><a href='$link'>$link</a></p>
                    <p>Si no solicitaste esto, puedes ignorar este correo.</p>
                ";
        }
        // Enviar el correo
        $mail->send();
        return true;
    } catch (Exception $e) {
        //echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
        return false;
    }
}
