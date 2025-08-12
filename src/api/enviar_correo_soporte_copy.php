<?php

// Asegúrate de que la variable esté definida antes de continuar
Auth::revisarVariableDefinida();

header("Content-type: application/json");

require "../phpMailer/src/PHPMailer.php";
require "../phpMailer/src/Exception.php";
require "../phpMailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer as Mailer;
use PHPMailer\PHPMailer\Exception as MailerException;
use PHPMailer\PHPMailer\SMTP;

$mail = new Mailer(true);

// 1. Leer los datos del formulario usando $_POST y $_FILES
$correoCliente = $_POST['correoCliente'] ?? "";
$nombreCliente = $_POST['nombreCliente'] ?? "";
$mensajeCliente = $_POST['mensajeCliente'] ?? "";

// Verificar los campos
if (empty(trim($correoCliente))) {
    echo json_encode([
        "estado" => "error",
        "respuesta" => "El correo del cliente está vacío."
    ]);
    exit;
} else if (empty(trim($nombreCliente))) {
    echo json_encode([
        "estado" => "error",
        "respuesta" => "El nombre del cliente está vacío."
    ]);
    exit;
} else if (empty(trim($mensajeCliente))) {
    echo json_encode([
        "estado" => "error",
        "respuesta" => "El mensaje del cliente está vacío."
    ]);
    exit;
}

try {
    // Configuración de PHPMailer
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Habilita la depuración completa
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = function($str, $level) {
        file_put_contents('phpmailer_debug.log', $level . ": " . $str . "\n", FILE_APPEND);  // Escribe el log en un archivo
    };

    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "aldextecnologystore@gmail.com"; // Reemplaza con tu dirección de correo
    $mail->Password = "izlomkqxjmtbggbe";  // Reemplaza con tu contraseña de aplicación de Gmail
    $mail->SMTPSecure = 'tls';  // Usar TLS para conexión segura
    $mail->Port = 587;  // Puerto para TLS

    // Dirección de envío
    $mail->setFrom('aldextecnologystore@gmail.com', 'Soporte Tecnico');
    $mail->addAddress($correoCliente, $nombreCliente);

    // Verificar si se ha adjuntado un archivo PDF
    if (isset($_FILES['file-pdf']) && $_FILES['file-pdf']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file-pdf']['tmp_name'];
        $fileName = $_FILES['file-pdf']['name'];
        $fileSize = $_FILES['file-pdf']['size'];
        $fileType = $_FILES['file-pdf']['type'];

        // Verificar si el archivo es un PDF
        if ($fileType === 'application/pdf') {
            // Verificar el tamaño del archivo (por ejemplo, no más de 5MB)
            if ($fileSize > 5 * 1024 * 1024) {  // Limite de 5MB
                echo json_encode([
                    "estado" => "error",
                    "respuesta" => "El archivo adjunto excede el tamaño máximo permitido de 5MB."
                ]);
                exit;
            }
            $mail->addAttachment($fileTmpPath, $fileName); // Adjuntar el archivo
        } else {
            echo json_encode([
                "estado" => "error",
                "respuesta" => "El archivo adjunto no es un PDF."
            ]);
            exit;
        }
    }

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Respuesta de la solicitud de contacto';
    $mail->Body = '<html lang="es">
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
                <h1>Hola '.$nombreCliente.'</h1>
            </div>
            <div class="content">
                <p>'.$mensajeCliente.'</p>
                <a href="#" class="button">Ver más</a>
            </div>
            <div class="footer">
                Este correo es solo para fines de prueba.
            </div>
        </div>
    </body>
    </html>';

    // Enviar el correo
    $mail->send();

    // Respuesta si todo sale bien
    echo json_encode([
        "estado" => "ok",
        "respuesta" => "Correo enviado correctamente."
    ]);
} catch (MailerException $e) {
    // Si ocurre un error al enviar el correo
    echo json_encode([
        "estado" => "error",
        "respuesta" => "Error al intentar enviar el correo. " . $e->getMessage()
    ]);
}

?>