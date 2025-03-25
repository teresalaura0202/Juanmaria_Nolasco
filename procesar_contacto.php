<!-- filepath: c:\xampp\htdocs\proyecto_clinica\procesar_contacto.php -->
<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $mensaje = trim($_POST['mensaje']);

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($email) || empty($telefono) || empty($mensaje)) {
        echo "<script>
            alert('Por favor, completa todos los campos.');
            window.history.back();
        </script>";
        exit;
    }

    // Configurar el correo
    $destinatario = "juanmariaesono@gmail.com"; // Cambia esto por el correo del destinatario
    $asunto = "Nuevo mensaje de contacto";
    $cuerpo = "
        <html>
        <head>
            <title>Nuevo mensaje de contacto</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f9;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }
                .email-header {
                    background-color: #007736;
                    color: #ffffff;
                    padding: 20px;
                    text-align: center;
                }
                .email-header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .email-header img {
                    max-width: 100px;
                    margin-bottom: 10px;
                }
                .email-body {
                    padding: 20px;
                    color: #333333;
                }
                .email-body h2 {
                    color: #007736;
                    font-size: 20px;
                    margin-bottom: 10px;
                }
                .email-body p {
                    margin: 10px 0;
                    line-height: 1.6;
                    font-size: 16px;
                }
                .email-body p strong {
                    color: #007736;
                }
                .email-footer {
                    background-color: #f4f4f9;
                    text-align: center;
                    padding: 10px;
                    font-size: 12px;
                    color: #666666;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header'>
                 
                    <h1>CLÍNICA MANOS UNIDAS</h1>
                </div>
                <div class='email-body'>
                    <h2>Nuevo mensaje de contacto</h2>
                    <p>📛 <strong>Nombre:</strong> $nombre</p>
                    <p>📧 <strong>Email:</strong> $email</p>
                    <p>📞 <strong>Teléfono:</strong> $telefono</p>
                    <p>💬 <strong>Mensaje:</strong></p>
                    <p>$mensaje</p>
                </div>
                <div class='email-footer'>
                    <p>Este mensaje fue enviado desde el formulario de contacto de CLÍNICA MANOS UNIDAS.</p>
                </div>
            </div>
        </body>
        </html>
    ";

    // Encabezados del correo
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: $email" . "\r\n";

    // Enviar el correo
    if (mail($destinatario, $asunto, $cuerpo, $headers)) {
        echo "<script>
            alert('Gracias por contactarnos. Hemos recibido tu mensaje.');
            window.location.href = 'contacto.php';
        </script>";
    } else {
        echo "<script>
            alert('Hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo.');
            window.history.back();
        </script>";
    }

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO contactos (nombre, email, telefono, mensaje) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $nombre, $email, $telefono, $mensaje);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Gracias por contactarnos. Hemos recibido tu mensaje.');
            window.location.href = 'contacto.php';
        </script>";
    } else {
        echo "<script>
            alert('Hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo.');
            window.history.back();
        </script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}
?>