<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['paciente_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_receta = $_GET['id'];

    // Verificar que la receta pertenezca al paciente
    $stmt = $conexion->prepare("
        SELECT r.archivo_path 
        FROM recetas r 
        INNER JOIN citas c ON r.id_cita = c.id_cita 
        WHERE r.id_receta = ? AND c.id_paciente = ?
    ");
    $stmt->bind_param("ii", $id_receta, $_SESSION['paciente_id']);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($receta = $resultado->fetch_assoc()) {
        $archivo = $receta['archivo_path'];

        if (file_exists($archivo)) {
            // Configurar headers para la descarga
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($archivo) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($archivo));

            // Leer y enviar el archivo
            readfile($archivo);
            exit;
        } else {
            echo "El archivo no existe.";
        }
    } else {
        echo "Receta no encontrada o no pertenece a este paciente.";
    }
} else {
    echo "ID de receta no especificado.";
}
?>