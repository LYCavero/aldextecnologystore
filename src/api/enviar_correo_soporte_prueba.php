<?php
session_start();

//Auth::revisarVariableDefinida();

// header("Content-type: application/json");

require "../phpMailer/src/PHPMailer.php";
require "../phpMailer/src/Exception.php";
require "../phpMailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer as Mailer;
use PHPMailer\PHPMailer\Exception as MailerException;
use PHPMailer\PHPMailer\SMTP;

$mail = new Mailer(true); //El true es para que me devuelva la excepcion del envio del correo en caso sucediera

// 1. Leer el cuerpo de la solicitud (JSON crudo)
//$inputJSON = file_get_contents("php://input");

// 2. Convertir JSON a arreglo asociativo de PHP
//$datos = json_decode($inputJSON, true);

$correoCliente = $_POST["txt-correo-cliente"] ?? "";
$nombreCliente = $_POST["txt-nombre-cliente"] ?? "";
$mensajeCliente = $_POST["txta-mensaje-cliente"] ?? "";

if (empty(trim($correoCliente))) {

//    echo json_encode([
//        "estado" => "error",
//        "respuesta" => "1. "
//    ]);
    $_SESSION["error"] = "El correo está vacío";
    // echo "error 1";
} else if (empty(trim($nombreCliente))) {

//    echo json_encode([
//        "estado" => "error",
//        "respuesta" => "2. "
//    ]);
    // echo "error 3";
    $_SESSION["error"] = "El nombre del cliente está vacío";
} else if (empty(trim($mensajeCliente))) {

//    echo json_encode([
//        "estado" => "error",
//        "respuesta" => "3. "
//    ]);
    // echo "error 3";
    $_SESSION["error"] = "El mensaje está vacío";
}

if(isset($_SESSION["error"])){
    header("Location: ../sistema/indexSistema.php");
    exit();
}

try {

    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->CharSet = 'UTF-8'; //esto sirve para que acepte carácteres como vocales con tildes
    $mail->isSMTP();
    $mail->Host         = "smtp.gmail.com"; //el tipo de correo que se usará para el envío de los correos
    $mail->SMTPAuth     = true;
    $mail->Username     = "aldextecnologystore@gmail.com"; //correo con el cual se utilizará para el envío de los mensajes
    $mail->Password     = "izlomkqxjmtbggbe"; //contraseña de aplicación generada, OJO: no es la contraseña de la cuenta que se usa para iniciar sesion
    $mail->SMTPSecure   = Mailer::ENCRYPTION_SMTPS;
    $mail->Port         = 465;

    $mail->setFrom('aldextecnologystore@gmail.com', 'Soporte Tecnico'); //correo que verán los clientes de quien envía el mensajeCliente
    // $mail->addAddress('caveroporroluis@gmail.com', 'Walter'); //nombre de la persona que veran quien envió el mensajeCliente
    $mail->addAddress($correoCliente, $nombreCliente); //nombre de la persona que veran quien envió el mensajeCliente


    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Respuesta de la solicitud de contacto';
    // $mail->Body    = 'This is the HTML message body <b>este sería el cuerpo</b>';
    $mail->Body    = '<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Correo de Prueba</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #222;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #003DA5;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px 20px;
            font-size: 16px;
            line-height: 1.5;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #09DDCC;
            color: #fff;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #555;
            padding: 15px 20px;
            background-color: #D1DDF5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hola '. 'Walter'.'</h1>
        </div>
        <div class="content">
            <p>'. $mensajeCliente .'</p>
            <a href="#" class="button">Ver más</a>
        </div>
        <div class="footer">
            Este correo es solo para fines de prueba.
        </div>
    </div>
</body>
</html>';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

    // Si llega aquí, el envío fue exitoso
    // echo json_encode([
    //     "estado" => "ok",
    //     "respuesta" => "Correo enviado correctamente."
    // ]);
    // echo "Correo enviado correctamente";
    $_SESSION["mensaje"] = "Correo enviado correctamente";
} catch (MailerException $e) {
    // echo json_encode([
    //     "estado" => "error",
    //     "respuesta" => "Error al intentar enviar el correo. " . $e
    // ]);
    echo "Error al intentar enviar el correo. " . $e;
}

header("Location: ../sistema/indexSistema.php");
exit();
