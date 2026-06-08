<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

function enviarCorreo($email, $fecha, $hora, $medico){

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'TUEMAIL@gmail.com';
        $mail->Password = 'TU_PASSWORD_APP';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('TUEMAIL@gmail.com', 'Clinica');
        $mail->addAddress($email);

        $mail->Subject = 'Confirmación de cita';
        $mail->Body = "Tu cita ha sido reservada:\nFecha: $fecha\nHora: $hora\nMédico: $medico";

        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar email";
    }
}