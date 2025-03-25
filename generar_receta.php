<?php
include 'conexion.php';

if(isset($_GET['id_receta'])) {
    $id_receta = $_GET['id_receta'];
    
    // Modificada la consulta para usar solo los campos existentes
    $query = "SELECT r.*, c.fecha_cita, p.nombre as nombre_paciente, p.email 
              FROM recetas r
              INNER JOIN citas c ON r.id_cita = c.id_cita 
              INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
              WHERE r.id_receta = ?";
              
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_receta);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $receta = mysqli_fetch_array($resultado);

    // Configurar headers para documento Word
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment; filename=receta_".$id_receta.".doc");

    // Crear el contenido HTML que será el documento Word
    echo '<!DOCTYPE html>';
    echo '<html>';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<style>
            body { font-family: Arial, sans-serif; }
            .header { text-align: center; margin-bottom: 30px; }
            .content { margin: 20px; }
            .section { margin-bottom: 20px; }
          </style>';
    echo '</head>';
    echo '<body>';
    echo '<div class="header">';
    echo '<h1>RECETA MÉDICA</h1>';
    echo '<h3>Fecha: '.$receta['fecha_cita'].'</h3>';
    echo '</div>';
    echo '<div class="content">';
    echo '<div class="section">';
    echo '<h3>DATOS DEL PACIENTE</h3>';
    echo '<p><strong>Nombre:</strong> '.$receta['nombre_paciente'].'</p>';
    echo '<p><strong>Email:</strong> '.$receta['email'].'</p>';
    echo '</div>';
    echo '<div class="section">';
    echo '<h3>PRESCRIPCIÓN MÉDICA</h3>';
    echo '<pre style="font-family: Arial, sans-serif;">'.$receta['prescripcion'].'</pre>';
    echo '</div>';
    echo '</div>';
    echo '</body>';
    echo '</html>';
}
?>