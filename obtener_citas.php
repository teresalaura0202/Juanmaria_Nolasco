<?php
include 'conexion.php';

$estado = isset($_GET['estado']) ? $_GET['estado'] : 'todos';

$sql = "SELECT c.*, p.nombre, p.email FROM citas c 
        INNER JOIN pacientes p ON c.id_paciente = p.id_paciente";

if ($estado !== 'todos') {
    $sql .= " WHERE c.estado = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $estado);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
} else {
    $resultado = mysqli_query($conexion, $sql);
}

$citas = array();
while ($fila = mysqli_fetch_assoc($resultado)) {
    $citas[] = $fila;
}

echo json_encode($citas);
?> 