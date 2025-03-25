<?php
// Incluir archivo de conexión
require_once('conexion.php');

// Archivo: guardar_caso.php - Para guardar un nuevo caso
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar campos requeridos
    $campos_requeridos = ['titulo', 'categoria', 'nombre_paciente', 'edad', 'testimonio'];
    
    foreach ($campos_requeridos as $campo) {
        if (empty($_POST[$campo])) {
            die("Error: Todos los campos son obligatorios");
        }
    }
    
    // Validar consentimiento
    if (!isset($_POST['consentimiento'])) {
        die("Error: Debe confirmar el consentimiento del paciente");
    }
    
    // Validar y procesar imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $imagen_info = getimagesize($_FILES['imagen']['tmp_name']);
        
        if ($imagen_info === false) {
            die("Error: El archivo subido no es una imagen válida");
        }
        
        // Validar tamaño (2MB máximo)
        if ($_FILES['imagen']['size'] > 2 * 1024 * 1024) {
            die("Error: La imagen no debe superar los 2MB");
        }
        
        // Validar tipo
        $tipos_permitidos = ['image/jpeg', 'image/png'];
        if (!in_array($_FILES['imagen']['type'], $tipos_permitidos)) {
            die("Error: Solo se permiten imágenes JPG y PNG");
        }
        
        // Guardar imagen
        $ruta_destino = './admin/uploads/';
        
        // Crear directorio si no existe
        if (!file_exists($ruta_destino)) {
            mkdir($ruta_destino, 0777, true);
        }
        
        // Generar nombre único para la imagen
        $nombre_archivo = uniqid() . '_' . $_FILES['imagen']['name'];
        $ruta_completa = $ruta_destino . $nombre_archivo;
        
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_completa)) {
            die("Error: No se pudo guardar la imagen");
        }
    } else {
        die("Error: Debe subir una imagen");
    }
    
    // Usar la conexión proporcionada (debe estar disponible desde conexion.php)
    global $conexion;
    
    // Preparar consulta SQL usando mysqli
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);
    $nombre_paciente = mysqli_real_escape_string($conexion, $_POST['nombre_paciente']);
    $edad = (int)$_POST['edad'];
    $testimonio = mysqli_real_escape_string($conexion, $_POST['testimonio']);
    $imagen = mysqli_real_escape_string($conexion, $ruta_completa);
    
    $sql = "INSERT INTO casos_exito (titulo, categoria, nombre_paciente, edad, testimonio, imagen) 
            VALUES ('$titulo', '$categoria', '$nombre_paciente', $edad, '$testimonio', '$imagen')";
    
    // Ejecutar consulta
    if (mysqli_query($conexion, $sql)) {
        // Redirigir al usuario
        header("Location: administrar_casos.php?mensaje=success");
        exit();
    } else {
        die("Error: " . mysqli_error($conexion));
    }
}

// Archivo: mostrar_casos.php - Para mostrar los casos en la página principal
function obtenerCasosExito() {
    global $conexion;
    
    $sql = "SELECT * FROM casos_exito WHERE activo = 1 ORDER BY fecha_registro DESC";
    $resultado = mysqli_query($conexion, $sql);
    
    $casos = [];
    
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $casos[] = $fila;
        }
    }
    
    return $casos;
}

// Ejemplo de uso para mostrar casos en la página principal
function mostrarCasosExito() {
    $casos = obtenerCasosExito();
    
    $html = '';
    
    foreach (array_chunk($casos, 2) as $fila) {
        $html .= '<div class="row">';
        
        foreach ($fila as $caso) {
            $html .= '
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <img src="' . htmlspecialchars($caso['imagen']) . '" 
                                 class="img-fluid rounded-start h-100" 
                                 alt="' . htmlspecialchars($caso['titulo']) . '"
                                 style="object-fit: cover;">
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <h5 class="card-title">' . htmlspecialchars($caso['titulo']) . '</h5>
                                <p class="card-text">' . htmlspecialchars($caso['nombre_paciente']) . ', ' . htmlspecialchars($caso['edad']) . ' años</p>
                                <p class="card-text text-muted">
                                    "' . htmlspecialchars($caso['testimonio']) . '"
                                </p>
                                <span class="badge bg-success">' . htmlspecialchars($caso['categoria']) . '</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
        
        $html .= '</div>';
    }
    
    return $html;
}

// Ejemplo de cómo usar la función
// echo mostrarCasosExito();
?>
