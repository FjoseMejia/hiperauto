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

function sendEmail($email_remitente, $email_destinatario, $password, $token, $typeEmail = "recoveryPassword") {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $email_remitente;
        $mail->Password = $password;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Configuración general del correo
        $mail->setFrom($email_remitente, 'HiperAuto');
        $mail->addAddress($email_destinatario);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->isHTML(true);

        // Enlace del proceso
        $link= $typeEmail == "register"
            ? "http://localhost/hiperAuto/app/View/index.php?email=$email_destinatario&token=" . urlencode($token)
            : "http://localhost/hiperAuto/app/View/recovery_password.php?email=$email_destinatario&token=" . urlencode($token);

        // Logo (coloca tu URL real aquí si lo tienes online)
        $logoURL= '/hiperAuto/assets/img/logo_hiperauto.png'; // <- REEMPLÁZALO con tu logo real

        // Contenido del mensaje
        $subject = $typeEmail == "register" ? "Bienvenido a HiperAuto" : "Restablecimiento de contraseña";
        $greeting = $typeEmail == "register"
            ? "¡Gracias por unirte a HiperAuto!"
            : "Hemos recibido una solicitud para restablecer tu contraseña.";

        $actionText = $typeEmail == "register"
            ? "Haz clic en el siguiente enlace para completar tu registro:"
            : "Haz clic en el siguiente enlace para restablecer tu contraseña:";

        $mail->Subject = $subject;
        $mail->Body = '
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; color: #333; }
                .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
                .logo { text-align: center; margin-bottom: 20px; }
                .logo img { max-width: 150px; }
                .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; }
                a.button {
                    display: inline-block;
                    margin-top: 15px;
                    padding: 10px 20px;
                    background-color: #0057B7;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="logo">
                    <img src="' . $logoURL . '" alt="HiperAuto">
                </div>
                <h2>' . $subject . '</h2>
                <p>' . $greeting . '</p>
                <p>' . $actionText . '</p>
                <p><a class="button" href="' . $link . '">Ir al enlace</a></p>
                <p>Si no solicitaste esto, puedes ignorar este correo sin problemas.</p>
                <div class="footer">
                    © ' . date("Y") . ' HiperAuto. Todos los derechos reservados.
                </div>
            </div>
        </body>
        </html>';
        
        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}
