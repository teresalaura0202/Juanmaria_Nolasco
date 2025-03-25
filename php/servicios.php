<?php

$mensaje = ''; // Variable para almacenar el mensaje

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    // Verificar si el nombre ya existe
    $sql_verificar = "SELECT id_paciente FROM pacientes WHERE nombre = ?";
    $stmt_verificar = mysqli_prepare($conexion, $sql_verificar);
    mysqli_stmt_bind_param($stmt_verificar, "s", $nombre);
    mysqli_stmt_execute($stmt_verificar);
    $resultado_verificar = mysqli_stmt_get_result($stmt_verificar);

    if (mysqli_num_rows($resultado_verificar) > 0) {
        // Si el nombre ya existe
        $mensaje = [
            'tipo' => 'error',
            'titulo' => 'paciente ya existe',
            'texto' => 'El nombre ya existe. Por favor, utiliza otro nombre.'
        ];
    } else {
        // Insertar el nuevo paciente
        $sql = "INSERT INTO pacientes (nombre, email, telefono) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $telefono);

        if (mysqli_stmt_execute($stmt)) {
            $id_paciente = mysqli_insert_id($conexion);

            // Insertar la cita asociada
            $sql = "INSERT INTO citas (id_paciente) VALUES (?)";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id_paciente);

            if (mysqli_stmt_execute($stmt)) {
                $mensaje = [
                    'tipo' => 'success',
                    'titulo' => '¡Éxito!',
                    'texto' => 'Su solicitud de cita ha sido enviada correctamente'
                ];
            }
        } else {
            $mensaje = [
                'tipo' => 'error',
                'titulo' => 'Error',
                'texto' => 'Hubo un problema al procesar su solicitud'
            ];
        }
    }
}
?>