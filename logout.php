<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Redirigir al login
header("Location: login.php");
exit;
?> 


<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Procesamiento del formulario para agregar un nuevo caso
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);
    $nombre_paciente = mysqli_real_escape_string($conexion, $_POST['nombre_paciente']);
    $edad = (int)$_POST['edad'];
    $testimonio = mysqli_real_escape_string($conexion, $_POST['testimonio']);
    $imagen = "";
    
    // Manejo de la imagen
    if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $directorio_destino = "uploads/";
        
        // Crear el directorio si no existe
        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }
        
        $nombre_archivo = time() . "_" . basename($_FILES["imagen"]["name"]);
        $ruta_destino = $directorio_destino . $nombre_archivo;
        
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta_destino)) {
            $imagen = $ruta_destino;
        } else {
            echo "<div class='alert alert-danger'>Error al subir la imagen.</div>";
        }
    }
    
    // Insertar en la base de datos
    $sql = "INSERT INTO casos_exito (titulo, categoria, nombre_paciente, edad, testimonio, imagen) 
            VALUES ('$titulo', '$categoria', '$nombre_paciente', $edad, '$testimonio', '$imagen')";
    
    if (mysqli_query($conexion, $sql)) {
        echo "<div class='alert alert-success'>Caso de éxito registrado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conexion) . "</div>";
    }
}

// Procesar la eliminación
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    
    // Obtener la ruta de la imagen antes de eliminar
    $sql_img = "SELECT imagen FROM casos_exito WHERE id = $id";
    $result_img = mysqli_query($conexion, $sql_img);
    
    if ($row_img = mysqli_fetch_assoc($result_img)) {
        $ruta_imagen = $row_img['imagen'];
        
        // Eliminar imagen del servidor
        if (!empty($ruta_imagen) && file_exists($ruta_imagen)) {
            unlink($ruta_imagen);
        }
    }
    
    // Eliminar registro de la base de datos
    $sql_eliminar = "DELETE FROM casos_exito WHERE id = $id";
    
    if (mysqli_query($conexion, $sql_eliminar)) {
        echo "<div class='alert alert-success'>Caso de éxito eliminado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar: " . mysqli_error($conexion) . "</div>";
    }
}

// Consultar todos los casos de éxito
$sql_select = "SELECT * FROM casos_exito ORDER BY fecha_registro DESC";
$result = mysqli_query($conexion, $sql_select);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Casos de Éxito</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .preview-image {
            max-width: 100px;
            max-height: 100px;
        }
        .table-responsive {
            margin-top: 30px;
        }
        .testimonio-cell {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4 text-center">Gestión de Casos de Éxito</h1>
        
        <!-- Formulario de Registro -->
        <div class="card mb-5">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Registrar Nuevo Caso de Éxito</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="titulo" class="form-label">Título*</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="col-md-6">
                            <label for="categoria" class="form-label">Categoría*</label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option value="">Seleccione una categoría</option>
                                <option value="Cirugía">Cirugía</option>
                                <option value="Tratamiento">Tratamiento</option>
                                <option value="Rehabilitación">Rehabilitación</option>
                                <option value="Diagnóstico">Diagnóstico</option>
                                <option value="Consulta">Consulta</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="nombre_paciente" class="form-label">Nombre del Paciente*</label>
                            <input type="text" class="form-control" id="nombre_paciente" name="nombre_paciente" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edad" class="form-label">Edad*</label>
                            <input type="number" class="form-control" id="edad" name="edad" min="0" max="120" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="testimonio" class="form-label">Testimonio*</label>
                        <textarea class="form-control" id="testimonio" name="testimonio" rows="4" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                        <div class="form-text">Seleccione una imagen del paciente o del procedimiento (opcional)</div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Caso de Éxito
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Tabla de Casos de Éxito -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="mb-0">Lista de Casos de Éxito</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Categoría</th>
                                <th>Paciente</th>
                                <th>Edad</th>
                                <th>Testimonio</th>
                                <th>Imagen</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['categoria']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['nombre_paciente']) . "</td>";
                                    echo "<td>" . $row['edad'] . "</td>";
                                    echo "<td class='testimonio-cell' title='" . htmlspecialchars($row['testimonio']) . "'>" . htmlspecialchars($row['testimonio']) . "</td>";
                                    echo "<td>";
                                    if (!empty($row['imagen']) && file_exists($row['imagen'])) {
                                        echo "<img src='" . $row['imagen'] . "' alt='Imagen del caso' class='preview-image'>";
                                    } else {
                                        echo "<span class='text-muted'>Sin imagen</span>";
                                    }
                                    echo "</td>";
                                    echo "<td>" . ($row['activo'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>') . "</td>";
                                    echo "<td>" . date('d/m/Y', strtotime($row['fecha_registro'])) . "</td>";
                                    echo "<td>
                                            <a href='?eliminar=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Está seguro de eliminar este caso?\")'>
                                                <i class='fas fa-trash'></i>
                                            </a>
                                            <a href='editar_caso.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm ms-1'>
                                                <i class='fas fa-edit'></i>
                                            </a>
                                         </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='10' class='text-center'>No hay casos de éxito registrados</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
mysqli_close($conexion);
?>