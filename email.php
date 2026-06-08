<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

function enviarCorreo($email, $nombre, $fecha, $hora, $medico){

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'correo@ejemplo.com';

        $mail->Password = 'PASSWORD_AQUI';

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // LO QUE VE EL USUARIO
        $mail->setFrom('clinicasaludplus@gmail.com', 'Clinica SaludPlus');

        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Confirmacion de cita';

        $mail->Body = "
        <div style='font-family:Arial;padding:20px'>
            <h2 style='color:#2c3e50'>Clinica SaludPlus</h2>

            <p>Hola <strong>$nombre</strong>,</p>

            <p>Su cita ha sido confirmada correctamente:</p>

            <ul>
                <li><strong>Fecha:</strong> $fecha</li>
                <li><strong>Hora:</strong> $hora</li>
                <li><strong>Medico:</strong> $medico</li>
            </ul>

            <p style='margin-top:20px'>
                Si desea cancelar su cita, pulse en el siguiente enlace:
            </p>

            <a href='http://localhost/chatbot-clinica/cancelar.php?id=$fecha$hora'
               style='background:#e74c3c;color:white;padding:10px 15px;border-radius:8px;text-decoration:none'>
               Cancelar cita
            </a>

            <p style='margin-top:30px'>Gracias por confiar en Clinica SaludPlus.</p>
        </div>
        ";

        $mail->send();

    } catch (Exception $e) {
        error_log("Error al enviar email: " . $mail->ErrorInfo);
    }
}