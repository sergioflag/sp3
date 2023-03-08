<?php

namespace configuration;

use configuration\algorithms as algo;

# archivos de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



class Mailer{

    public static function enviarCorreo($data){

        $folio = algo::folioGenerator();

        #Instancia de conexión
        $mail = new PHPMailer(true);
        #Congiguraciones del servidor
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host       = 'smtp.gmail.com'; //Set the SMTP server to send through
        $mail->SMTPAuth   = true; //Enable SMTP authentication
        $mail->Username   = 'usuariodebrian@gmail.com'; //SMTP username tu email Brian
        $mail->Password   = 'tokendebrianphpmailer'; //SMTP password Tu password (que extrajiste anoche PHPMailer)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port       = 465;

        #Datos del emisor y receptor
        $mail->setFrom("usuariodebrian@gmail.com",'Administrador');
        $mail->addAddress($data->email,"{$data->nombre}");

            #Configuracion del contenido de envío
            $mail->isHTML(true);
            $mail->Subject = "Actualización de contraseña";
            $mail->AltBody = "Su contrasena se ha actualizado";
            $mail->Body = "
            <div>
                <h3>Hola {$data->nombre}</h3>
                <p>Te informamos que tu contraseña ha sido actualizada. Folio:{$folio}</p>
            </div>
            ";

            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            if($mail->send()){
                return true;
            }else{
                return false;
            }


    }
}

?>