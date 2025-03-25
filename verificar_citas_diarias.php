<?php
include 'conexion.php';

// Verificar citas para hoy
$sql = "SELECT c.id_cita, c.id_paciente, c.fecha_cita, c.hora_cita, p.nombre, p.email 
        FROM citas c 
        INNER JOIN pacientes p ON c.id_paciente = p.id_paciente 
        WHERE c.fecha_cita = CURDATE() 
        AND c.estado = 'aprobada'
        AND NOT EXISTS (
            SELECT 1 FROM notificaciones n 
            WHERE n.id_paciente = c.id_paciente 
            AND n.fecha_cita = c.fecha_cita
        )";

$resultado = mysqli_query($conexion, $sql);

while ($fila = mysqli_fetch_assoc($resultado)) {
    // Crear notificación en la base de datos
    $mensaje = "Recordatorio: Tienes una cita médica programada para hoy a las " . $fila['hora_cita'];
    
    $sql_insert = "INSERT INTO notificaciones (id_paciente, mensaje, fecha_cita) 
                   VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql_insert);
    mysqli_stmt_bind_param($stmt, "iss", $fila['id_paciente'], $mensaje, $fila['fecha_cita']);
    mysqli_stmt_execute($stmt);
    
    // Enviar email de recordatorio
    $asunto = "Recordatorio de Cita Médica - " . date('d/m/Y');
    $mensaje_email = "Estimado/a " . $fila['nombre'] . ",\n\n" .
                    "Le recordamos que tiene una cita médica programada para hoy a las " . $fila['hora_cita'] . ".\n\n" .
                    "Por favor, llegue con 15 minutos de anticipación.\n\n" .
                    "Saludos cordiales,\n" .
                    "Clínica Médica";
    
    mail($fila['email'], $asunto, $mensaje_email);
}

// Verificar citas para mañana
$sql = "SELECT c.id_cita, c.id_paciente, c.fecha_cita, c.hora_cita, p.nombre, p.email 
        FROM citas c 
        INNER JOIN pacientes p ON c.id_paciente = p.id_paciente 
        WHERE c.fecha_cita = DATE_ADD(CURDATE(), INTERVAL 1 DAY) 
        AND c.estado = 'aprobada'
        AND NOT EXISTS (
            SELECT 1 FROM notificaciones n 
            WHERE n.id_paciente = c.id_paciente 
            AND n.fecha_cita = c.fecha_cita
        )";

$resultado = mysqli_query($conexion, $sql);

while ($fila = mysqli_fetch_assoc($resultado)) {
    // Crear notificación en la base de datos
    $mensaje = "Recordatorio: Tienes una cita médica programada para mañana a las " . $fila['hora_cita'];
    
    $sql_insert = "INSERT INTO notificaciones (id_paciente, mensaje, fecha_cita) 
                   VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql_insert);
    mysqli_stmt_bind_param($stmt, "iss", $fila['id_paciente'], $mensaje, $fila['fecha_cita']);
    mysqli_stmt_execute($stmt);
    
    // Enviar email de recordatorio
    $asunto = "Recordatorio de Cita Médica - Mañana " . date('d/m/Y', strtotime('+1 day'));
    $mensaje_email = "Estimado/a " . $fila['nombre'] . ",\n\n" .
                    "Le recordamos que tiene una cita médica programada para mañana a las " . $fila['hora_cita'] . ".\n\n" .
                    "Por favor, llegue con 15 minutos de anticipación.\n\n" .
                    "Saludos cordiales,\n" .
                    "Clínica Médica";
    
    mail($fila['email'], $asunto, $mensaje_email);
}

echo "Verificación de citas completada: " . date('Y-m-d H:i:s');
?> 