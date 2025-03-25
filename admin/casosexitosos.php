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
        $mensaje_exito = "Caso de éxito registrado correctamente.";
    } else {
        $mensaje_error = "Error: " . mysqli_error($conexion);
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
        $mensaje_exito = "Caso de éxito eliminado correctamente.";
    } else {
        $mensaje_error = "Error al eliminar: " . mysqli_error($conexion);
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
  
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../estilos/cas.css">
   
</head>
<body>
    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-star me-3"></i>Gestión de Casos de Éxito</h1>
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalFormulario">
                    <i class="fas fa-plus me-2"></i>Nuevo Caso
                </button>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (isset($mensaje_exito)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $mensaje_exito; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <?php if (isset($mensaje_error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $mensaje_error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <!-- Tabla de Casos de Éxito -->
        <div class="card">
            <div class="card-header bg-white py-3">
                <h3 class="m-0 text-primary"><i class="fas fa-list-alt me-2"></i>Lista de Casos de Éxito</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                <th><i class="fas fa-heading me-2"></i>Título</th>
                                <th><i class="fas fa-tag me-2"></i>Categoría</th>
                                <th><i class="fas fa-user me-2"></i>Paciente</th>
                                <th><i class="fas fa-birthday-cake me-2"></i>Edad</th>
                                <th><i class="fas fa-comment me-2"></i>Testimonio</th>
                                <th><i class="fas fa-image me-2"></i>Imagen</th>
                                <th><i class="fas fa-toggle-on me-2"></i>Estado</th>
                                <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                                <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                                    echo "<td><span class='badge bg-info'>" . htmlspecialchars($row['categoria']) . "</span></td>";
                                    echo "<td>" . htmlspecialchars($row['nombre_paciente']) . "</td>";
                                    echo "<td>" . $row['edad'] . "</td>";
                                    echo "<td class='testimonio-cell' title='" . htmlspecialchars($row['testimonio']) . "'>" . htmlspecialchars($row['testimonio']) . "</td>";
                                    echo "<td>";
                                    if (!empty($row['imagen']) && file_exists($row['imagen'])) {
                                        echo "<img src='" . $row['imagen'] . "' alt='Imagen del caso' class='preview-image'>";
                                    } else {
                                        echo "<span class='text-muted'><i class='fas fa-camera-slash'></i></span>";
                                    }
                                    echo "</td>";
                                    echo "<td>" . ($row['activo'] ? '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Activo</span>' : '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Inactivo</span>') . "</td>";
                                    echo "<td>" . date('d/m/Y', strtotime($row['fecha_registro'])) . "</td>";
                                    echo "<td>
                                            <a href='?eliminar=" . $row['id'] . "' class='btn btn-danger action-btn' onclick='return confirm(\"¿Está seguro de eliminar este caso?\")'>
                                                <i class='fas fa-trash'></i>
                                            </a>
                                            <button class='btn btn-warning action-btn' onclick='editarCaso(" . json_encode($row) . ")'>
                                                <i class='fas fa-edit'></i>
                                            </button>
                                            <button class='btn btn-info action-btn' onclick='verDetalle(" . json_encode($row) . ")'>
                                                <i class='fas fa-eye'></i>
                                            </button>
                                         </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='10'>
                                    <div class='empty-state'>
                                        <i class='fas fa-folder-open'></i>
                                        <h4>No hay casos de éxito registrados</h4>
                                        <p>Empiece agregando su primer caso de éxito</p>
                                        <button class='btn btn-primary mt-3' data-bs-toggle='modal' data-bs-target='#modalFormulario'>
                                            <i class='fas fa-plus me-2'></i>Agregar Caso
                                        </button>
                                    </div>
                                </td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón flotante para agregar -->
    <button class="btn btn-primary btn-float" data-bs-toggle="modal" data-bs-target="#modalFormulario">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Modal de Formulario -->
    <div class="modal fade" id="modalFormulario" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel"><i class="fas fa-plus-circle me-2"></i>Registrar Nuevo Caso de Éxito</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" id="formCasoExito">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="titulo" class="form-label"><i class="fas fa-heading"></i>Título*</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                            <div class="col-md-6">
                                <label for="categoria" class="form-label"><i class="fas fa-tag"></i>Categoría*</label>
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
                                <label for="nombre_paciente" class="form-label"><i class="fas fa-user"></i>Nombre del Paciente*</label>
                                <input type="text" class="form-control" id="nombre_paciente" name="nombre_paciente" required>
                            </div>
                            <div class="col-md-4">
                                <label for="edad" class="form-label"><i class="fas fa-birthday-cake"></i>Edad*</label>
                                <input type="number" class="form-control" id="edad" name="edad" min="0" max="120" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="testimonio" class="form-label"><i class="fas fa-comment"></i>Testimonio*</label>
                            <textarea class="form-control" id="testimonio" name="testimonio" rows="4" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-image"></i>Imagen</label>
                            <label for="imagen" class="custom-file-upload d-block">
                                <input type="file" class="d-none" id="imagen" name="imagen" accept="image/*" onchange="previewImage(this)">
                                <i class="fas fa-cloud-upload-alt d-block"></i>
                                <p class="mb-0">Arrastre una imagen aquí o haga clic para seleccionar</p>
                                <small class="text-muted d-block mt-2">Formatos permitidos: JPG, PNG, GIF</small>
                                <img id="imagen-preview" src="#" alt="Vista previa">
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" form="formCasoExito" name="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Caso de Éxito
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles -->
    <div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detalles del Caso</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div id="detalle-imagen-container" class="mb-3">
                                <!-- La imagen se insertará aquí -->
                            </div>
                            <h5 id="detalle-paciente" class="mb-1"></h5>
                            <p id="detalle-edad" class="text-muted"></p>
                        </div>
                        <div class="col-md-8">
                            <h4 id="detalle-titulo" class="mb-2"></h4>
                            <div class="mb-3">
                                <span id="detalle-categoria" class="badge bg-info me-2"></span>
                                <span id="detalle-fecha" class="text-muted"></span>
                            </div>
                            <h5><i class="fas fa-quote-left me-2 text-muted"></i>Testimonio</h5>
                            <p id="detalle-testimonio" class="p-3 bg-light rounded"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Vista previa de imagen
        function previewImage(input) {
            const preview = document.getElementById('imagen-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }
        
        // Función para ver detalles
        function verDetalle(caso) {
            document.getElementById('detalle-titulo').innerText = caso.titulo;
            document.getElementById('detalle-paciente').innerText = caso.nombre_paciente;
            document.getElementById('detalle-edad').innerText = caso.edad + ' años';
            document.getElementById('detalle-categoria').innerText = caso.categoria;
            document.getElementById('detalle-testimonio').innerText = caso.testimonio;
            document.getElementById('detalle-fecha').innerText = 'Registrado: ' + new Date(caso.fecha_registro).toLocaleDateString();
            
            const imagenContainer = document.getElementById('detalle-imagen-container');
            if (caso.imagen && caso.imagen !== '') {
                imagenContainer.innerHTML = `<img src="${caso.imagen}" alt="Imagen del caso" class="img-fluid rounded">`;
            } else {
                imagenContainer.innerHTML = `<div class="bg-light rounded p-5 text-center text-muted">
                    <i class="fas fa-image fa-4x mb-3"></i>
                    <p>Sin imagen</p>
                </div>`;
            }
            
            const modalDetalle = new bootstrap.Modal(document.getElementById('modalDetalle'));
            modalDetalle.show();
        }
        
        // Función para editar caso (implementación simplificada)
        function editarCaso(caso) {
            // Abrir el modal y llenar campos
            document.getElementById('titulo').value = caso.titulo;
            document.getElementById('categoria').value = caso.categoria;
            document.getElementById('nombre_paciente').value = caso.nombre_paciente;
            document.getElementById('edad').value = caso.edad;
            document.getElementById('testimonio').value = caso.testimonio;
            
            // Cambiar el título del modal
            document.getElementById('modalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Editar Caso de Éxito';
            
            // Mostrar el modal
            const modalFormulario = new bootstrap.Modal(document.getElementById('modalFormulario'));
            modalFormulario.show();
            
            // Nota: En una implementación real, añadirías un campo oculto con el ID
            // y modificarías el procesamiento del formulario para actualizar en lugar de insertar
        }
        
        // Ocultar alertas después de 5 segundos
        setTimeout(function() {
            const alertas = document.querySelectorAll('.alert');
            alertas.forEach(function(alerta) {
                const bsAlert = new bootstrap.Alert(alerta);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>

<?php
// Cerrar la conexión
mysqli_close($conexion);
?>